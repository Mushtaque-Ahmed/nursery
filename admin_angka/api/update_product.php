<?php

/*
|--------------------------------------------------------------------------
| ERROR HANDLING
|--------------------------------------------------------------------------
|
| Do not display PHP warnings/notices because they would break JSON.
|
|--------------------------------------------------------------------------
*/

ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);
error_reporting(E_ALL);

header("Content-Type: application/json");


/*
|--------------------------------------------------------------------------
| REQUIRE FILES
|--------------------------------------------------------------------------
*/

require_once "db.php";
require_once "image_helper.php";


/*
|--------------------------------------------------------------------------
| ONLY POST
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Only POST requests are allowed"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| PRODUCT ID
|--------------------------------------------------------------------------
*/

$id = isset($_POST["id"])
    ? (int) $_POST["id"]
    : 0;


if ($id <= 0) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Invalid product ID"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| GET FORM DATA
|--------------------------------------------------------------------------
*/

$name =
    trim($_POST["name"] ?? "");


$category =
    trim($_POST["category"] ?? "");


$price =
    $_POST["price"] ?? "";


$salePrice =
    $_POST["sale_price"] ?? "";


$stock =
    $_POST["stock"] ?? 0;


$description =
    trim($_POST["description"] ?? "");


$available =
    isset($_POST["available"])
        ? (int) $_POST["available"]
        : 0;


$featured =
    isset($_POST["featured"])
        ? (int) $_POST["featured"]
        : 0;


/*
|--------------------------------------------------------------------------
| VALIDATION
|--------------------------------------------------------------------------
*/

if ($name === "") {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Plant name is required"
    ]);

    exit;
}


if ($category === "") {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Category is required"
    ]);

    exit;
}


if (!is_numeric($price) || $price < 0) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Invalid price"
    ]);

    exit;
}


if (!is_numeric($stock) || $stock < 0) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Invalid stock"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| SALE PRICE
|--------------------------------------------------------------------------
*/

if ($salePrice === "") {

    $salePrice = null;

} elseif (
    !is_numeric($salePrice) ||
    $salePrice < 0
) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Invalid sale price"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| VARIABLES
|--------------------------------------------------------------------------
*/

$oldImagePath = null;

$imagePath = null;

$newImageFullPath = null;


/*
|--------------------------------------------------------------------------
| GET EXISTING PRODUCT
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->prepare("
        SELECT
            id,
            image
        FROM plants
        WHERE id = :id
        LIMIT 1
    ");


    $stmt->execute([
        ":id" => $id
    ]);


    $existingProduct =
        $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$existingProduct) {

        http_response_code(404);

        echo json_encode([
            "success" => false,
            "message" => "Product not found"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | KEEP OLD IMAGE
    |--------------------------------------------------------------------------
    */

    $oldImagePath =
        $existingProduct["image"];


    /*
    |--------------------------------------------------------------------------
    | DEFAULT IMAGE
    |--------------------------------------------------------------------------
    |
    | If user doesn't upload a new image,
    | keep the existing image.
    |
    */

    $imagePath =
        $oldImagePath;


    /*
    |--------------------------------------------------------------------------
    | NEW IMAGE UPLOADED?
    |--------------------------------------------------------------------------
    */

    $hasNewImage =
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE;


    if ($hasNewImage) {

        /*
        |--------------------------------------------------------------------------
        | UPLOAD ERROR
        |--------------------------------------------------------------------------
        */

        if (
            $_FILES["image"]["error"]
            !== UPLOAD_ERR_OK
        ) {

            http_response_code(422);

            echo json_encode([
                "success" => false,
                "message" => "Image upload failed"
            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | ORIGINAL FILE SIZE
        |--------------------------------------------------------------------------
        |
        | Maximum original upload = 5 MB
        |
        */

        if (
            $_FILES["image"]["size"]
            > 5 * 1024 * 1024
        ) {

            http_response_code(422);

            echo json_encode([
                "success" => false,
                "message" =>
                    "Original image must be smaller than 5MB"
            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK MIME TYPE
        |--------------------------------------------------------------------------
        */

        $finfo =
            new finfo(FILEINFO_MIME_TYPE);


        $mime =
            $finfo->file(
                $_FILES["image"]["tmp_name"]
            );


        $allowedTypes = [

            "image/jpeg",

            "image/png",

            "image/webp",

            "image/gif"

        ];


        if (
            !in_array(
                $mime,
                $allowedTypes,
                true
            )
        ) {

            http_response_code(422);

            echo json_encode([
                "success" => false,
                "message" =>
                    "Only JPG, PNG, GIF and WebP images are allowed"
            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | UPLOAD DIRECTORY
        |--------------------------------------------------------------------------
        */

        $uploadDirectory =
            dirname(__DIR__) .
            "/uploads/plants/";


        /*
        |--------------------------------------------------------------------------
        | COMPRESS + CONVERT
        |--------------------------------------------------------------------------
        |
        | The helper will:
        |
        | JPG / PNG / GIF / WebP
        |          ↓
        |      Resize
        |          ↓
        |       WebP
        |          ↓
        |      Compress
        |          ↓
        |       <= 20 KB
        |
        |--------------------------------------------------------------------------
        */

        try {

            $compressedImage =
                compressImageToWebP(

                    $_FILES["image"]["tmp_name"],

                    $_FILES["image"]["name"],

                    $uploadDirectory,

                    20

                );


            if (
                !$compressedImage ||
                empty($compressedImage["filename"])
            ) {

                throw new Exception(
                    "Could not compress image."
                );
            }


        } catch (Throwable $e) {

            http_response_code(500);

            echo json_encode([
                "success" => false,
                "message" => $e->getMessage()
            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | NEW IMAGE PATH
        |--------------------------------------------------------------------------
        */

        $imagePath =
            "uploads/plants/" .
            $compressedImage["filename"];


        /*
        |--------------------------------------------------------------------------
        | FULL SERVER PATH
        |--------------------------------------------------------------------------
        */

        $newImageFullPath =
            dirname(__DIR__) .
            "/" .
            $imagePath;


        /*
        |--------------------------------------------------------------------------
        | VERIFY NEW IMAGE
        |--------------------------------------------------------------------------
        */

        if (
            !file_exists(
                $newImageFullPath
            )
        ) {

            http_response_code(500);

            echo json_encode([
                "success" => false,
                "message" =>
                    "Compressed image was not created"
            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | VERIFY <= 20 KB
        |--------------------------------------------------------------------------
        */

        $newImageSize =
            filesize(
                $newImageFullPath
            );


        if (
            $newImageSize >
            (20 * 1024)
        ) {

            @unlink(
                $newImageFullPath
            );


            http_response_code(500);

            echo json_encode([
                "success" => false,
                "message" =>
                    "Could not compress image below 20 KB"
            ]);

            exit;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE DATABASE
    |--------------------------------------------------------------------------
    */

    $sql = "

        UPDATE plants

        SET

            name = :name,

            category = :category,

            price = :price,

            sale_price = :sale_price,

            stock = :stock,

            description = :description,

            image = :image,

            available = :available,

            featured = :featured

        WHERE id = :id

    ";


    $stmt =
        $pdo->prepare($sql);


    $stmt->execute([

        ":name" =>
            $name,

        ":category" =>
            $category,

        ":price" =>
            $price,

        ":sale_price" =>
            $salePrice,

        ":stock" =>
            (int) $stock,

        ":description" =>
            $description,

        ":image" =>
            $imagePath,

        ":available" =>
            $available ? 1 : 0,

        ":featured" =>
            $featured ? 1 : 0,

        ":id" =>
            $id

    ]);


    /*
    |--------------------------------------------------------------------------
    | DELETE OLD IMAGE
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Only delete the old image AFTER database update succeeds.
    |
    |--------------------------------------------------------------------------
    */

    if (
        $hasNewImage &&
        !empty($oldImagePath) &&
        $oldImagePath !== $imagePath
    ) {

        $oldImageFullPath =
            dirname(__DIR__) .
            "/" .
            $oldImagePath;


        if (
            file_exists(
                $oldImageFullPath
            )
        ) {

            @unlink(
                $oldImageFullPath
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | IMAGE SIZE
    |--------------------------------------------------------------------------
    */

   $imageSize = null;

    $imageSizeKB = null;


    if (
        $hasNewImage &&
        $newImageFullPath &&
        file_exists($newImageFullPath)
    ) {

        $imageSize =
            filesize(
                $newImageFullPath
            );


        $imageSizeKB =
            round(
                $imageSize / 1024,
                2
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        "success" =>
            true,

        "message" =>
            "Plant updated successfully",

        "product_id" =>
            $id,

        "image" =>
            $imagePath,

        "new_image" =>
            $hasNewImage,

        "image_size" =>
            $imageSize,

        "image_size_kb" =>
            $imageSizeKB

    ]);

    exit;


} catch (PDOException $e) {

    /*
    |--------------------------------------------------------------------------
    | DATABASE ERROR
    |--------------------------------------------------------------------------
    |
    | If a new compressed image was created but database
    | update failed, remove the new image.
    |
    |--------------------------------------------------------------------------
    */

    if (
        $newImageFullPath &&
        file_exists(
            $newImageFullPath
        )
    ) {

        @unlink(
            $newImageFullPath
        );
    }


    http_response_code(500);

    echo json_encode([

        "success" =>
            false,

        "message" =>
            "Could not update product"

    ]);

    exit;


} catch (Throwable $e) {

    /*
    |--------------------------------------------------------------------------
    | GENERAL ERROR
    |--------------------------------------------------------------------------
    */

    if (
        $newImageFullPath &&
        file_exists(
            $newImageFullPath
        )
    ) {

        @unlink(
            $newImageFullPath
        );
    }


    http_response_code(500);

    echo json_encode([

        "success" =>
            false,

        "message" =>
            $e->getMessage()

    ]);

    exit;
}

?>