<?php

/*
|--------------------------------------------------------------------------
| ERROR HANDLING
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
| IMAGE CONFIGURATION
|--------------------------------------------------------------------------
*/

$maxImageSizeKB = 100;
$maxOriginalSize = 5 * 1024 * 1024; // 5 MB


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

$name = trim($_POST["name"] ?? "");

$category = trim($_POST["category"] ?? "");

$price = $_POST["price"] ?? "";

$salePrice = $_POST["sale_price"] ?? "";

$stock = $_POST["stock"] ?? 0;

$description = trim($_POST["description"] ?? "");

$available = isset($_POST["available"])
    ? (int) $_POST["available"]
    : 0;

$featured = isset($_POST["featured"])
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

$hasNewImage = false;

$newImageSize = null;


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
    | KEEP OLD IMAGE BY DEFAULT
    |--------------------------------------------------------------------------
    */

    $oldImagePath =
        $existingProduct["image"];

    $imagePath =
        $oldImagePath;


    /*
    |--------------------------------------------------------------------------
    | CHECK IF NEW IMAGE WAS UPLOADED
    |--------------------------------------------------------------------------
    */

    $hasNewImage =
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE;


    /*
    |--------------------------------------------------------------------------
    | PROCESS NEW IMAGE
    |--------------------------------------------------------------------------
    */

    if ($hasNewImage) {

        /*
        |--------------------------------------------------------------------------
        | CHECK UPLOAD ERROR
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


        $file = $_FILES["image"];


        /*
        |--------------------------------------------------------------------------
        | ORIGINAL FILE SIZE
        |--------------------------------------------------------------------------
        */

        if (
            $file["size"]
            > $maxOriginalSize
        ) {

            http_response_code(422);

            echo json_encode([
                "success" => false,
                "message" =>
                    "Original image must be smaller than 5 MB"
            ]);

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK REAL MIME TYPE
        |--------------------------------------------------------------------------
        */

        $finfo =
            new finfo(FILEINFO_MIME_TYPE);


        $mime =
            $finfo->file(
                $file["tmp_name"]
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
        | COMPRESS + CONVERT TO WEBP
        |--------------------------------------------------------------------------
        */

        try {

            $compressedImage =
                compressImageToWebP(

                    $file["tmp_name"],

                    $file["name"],

                    $uploadDirectory,

                    $maxImageSizeKB

                );


            if (
                !$compressedImage ||
                empty($compressedImage["filename"])
            ) {

                throw new Exception(
                    "Could not compress image."
                );
            }


            /*
            |--------------------------------------------------------------------------
            | NEW DATABASE IMAGE PATH
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
            | VERIFY IMAGE EXISTS
            |--------------------------------------------------------------------------
            */

            if (
                !file_exists(
                    $newImageFullPath
                )
            ) {

                throw new Exception(
                    "Compressed image was not created"
                );
            }


            clearstatcache(
                true,
                $newImageFullPath
            );


            /*
            |--------------------------------------------------------------------------
            | GET FINAL IMAGE SIZE
            |--------------------------------------------------------------------------
            */

            $newImageSize =
                filesize(
                    $newImageFullPath
                );


            /*
            |--------------------------------------------------------------------------
            | FINAL IMAGE SIZE CHECK
            |--------------------------------------------------------------------------
            */

            if (
                $newImageSize >
                ($maxImageSizeKB * 1024)
            ) {

                if (
                    file_exists(
                        $newImageFullPath
                    )
                ) {

                    unlink(
                        $newImageFullPath
                    );
                }


                throw new Exception(
                    "Could not compress image below {$maxImageSizeKB} KB"
                );
            }


        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | DELETE FAILED NEW IMAGE
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
                "success" => false,
                "message" => $e->getMessage()
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
    | Only delete after successful database update.
    |
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
    | IMAGE SIZE RESPONSE
    |--------------------------------------------------------------------------
    */

    $imageSize = null;

    $imageSizeKB = null;


    if (
        $hasNewImage &&
        $newImageFullPath &&
        file_exists(
            $newImageFullPath
        )
    ) {

        clearstatcache(
            true,
            $newImageFullPath
        );


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
    | SUCCESS RESPONSE
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
    | Delete new image if database update fails.
    |
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