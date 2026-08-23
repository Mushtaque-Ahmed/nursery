<?php

ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);
error_reporting(E_ALL);

header("Content-Type: application/json");

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
| REQUEST METHOD
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
        "message" => "Invalid stock quantity"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| SALE PRICE VALIDATION
|--------------------------------------------------------------------------
*/

if ($salePrice === "") {

    $salePrice = null;

} elseif (!is_numeric($salePrice) || $salePrice < 0) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Invalid sale price"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| IMAGE VALIDATION
|--------------------------------------------------------------------------
*/

if (
    !isset($_FILES["image"]) ||
    $_FILES["image"]["error"] === UPLOAD_ERR_NO_FILE
) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Plant image is required"
    ]);

    exit;
}


if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {

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

if ($file["size"] > $maxOriginalSize) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Original image must be smaller than 5 MB"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| CHECK REAL MIME TYPE
|--------------------------------------------------------------------------
*/

$finfo = new finfo(FILEINFO_MIME_TYPE);

$mime = $finfo->file($file["tmp_name"]);


$allowedTypes = [
    "image/jpeg",
    "image/png",
    "image/webp",
    "image/gif"
];


if (!in_array($mime, $allowedTypes, true)) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Only JPG, PNG, GIF and WebP images are allowed"
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

$imagePath = null;
$imageInfo = null;
$fullImagePath = null;


try {

    $imageInfo = compressImageToWebP(
        $file["tmp_name"],
        $file["name"],
        $uploadDirectory,
        $maxImageSizeKB
    );


    if (
        !$imageInfo ||
        empty($imageInfo["filename"])
    ) {

        throw new Exception(
            "Could not compress image."
        );

    }


    /*
    |--------------------------------------------------------------------------
    | DATABASE IMAGE PATH
    |--------------------------------------------------------------------------
    */

    $imagePath =
        "uploads/plants/" .
        $imageInfo["filename"];


    /*
    |--------------------------------------------------------------------------
    | FULL SERVER PATH
    |--------------------------------------------------------------------------
    */

    $fullImagePath =
        dirname(__DIR__) .
        "/" .
        $imagePath;


    /*
    |--------------------------------------------------------------------------
    | VERIFY IMAGE EXISTS
    |--------------------------------------------------------------------------
    */

    if (!file_exists($fullImagePath)) {

        throw new Exception(
            "Compressed image was not created."
        );
    }


    clearstatcache(
        true,
        $fullImagePath
    );


    $finalImageSize =
        filesize($fullImagePath);


    /*
    |--------------------------------------------------------------------------
    | FINAL IMAGE SIZE CHECK
    |--------------------------------------------------------------------------
    */

    if (
        $finalImageSize >
        ($maxImageSizeKB * 1024)
    ) {

        if (file_exists($fullImagePath)) {
            unlink($fullImagePath);
        }

        throw new Exception(
            "Could not compress image below {$maxImageSizeKB} KB"
        );
    }


} catch (Throwable $e) {

    /*
    |--------------------------------------------------------------------------
    | DELETE IMAGE IF ERROR OCCURS
    |--------------------------------------------------------------------------
    */

    if (
        $fullImagePath &&
        file_exists($fullImagePath)
    ) {

        unlink($fullImagePath);
    }


    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| INSERT PRODUCT
|--------------------------------------------------------------------------
*/

try {

    $sql = "
        INSERT INTO plants
        (
            name,
            category,
            price,
            sale_price,
            stock,
            description,
            image,
            available,
            featured
        )
        VALUES
        (
            :name,
            :category,
            :price,
            :sale_price,
            :stock,
            :description,
            :image,
            :available,
            :featured
        )
    ";


    $stmt = $pdo->prepare($sql);


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
            $featured ? 1 : 0

    ]);


    /*
    |--------------------------------------------------------------------------
    | GET PRODUCT ID
    |--------------------------------------------------------------------------
    */

    $productId =
        $pdo->lastInsertId();


    /*
    |--------------------------------------------------------------------------
    | SUCCESS RESPONSE
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        "success" => true,

        "message" =>
            "Plant added successfully",

        "product_id" =>
            $productId,

        "image" =>
            $imagePath,

        "image_size" =>
            $finalImageSize,

        "image_size_kb" =>
            round(
                $finalImageSize / 1024,
                2
            )

    ]);

} catch (PDOException $e) {

    /*
    |--------------------------------------------------------------------------
    | DATABASE FAILED
    | DELETE UPLOADED IMAGE
    |--------------------------------------------------------------------------
    */

    if (
        $imagePath &&
        $fullImagePath &&
        file_exists($fullImagePath)
    ) {

        unlink($fullImagePath);
    }


    http_response_code(500);

    echo json_encode([

        "success" => false,

        "message" =>
            "Could not add product"

    ]);

}

?>