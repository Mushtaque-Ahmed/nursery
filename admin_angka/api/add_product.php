<?php

ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once "db.php";
require_once "image_helper.php";


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
| SALE PRICE
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
| IMAGE
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
|
| Maximum upload before compression = 5 MB
|--------------------------------------------------------------------------
*/

if ($file["size"] > 5 * 1024 * 1024) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Original image must be smaller than 5MB"
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
|
| Target:
| BELOW 20 KB
|--------------------------------------------------------------------------
*/

$imagePath = null;
$imageInfo = null;


try {

    $imageInfo = compressImageToWebP(
        $file["tmp_name"],
        $file["name"],
        $uploadDirectory,
        20
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
    | Database path
    |--------------------------------------------------------------------------
    */

    $imagePath =
        "uploads/plants/" .
        $imageInfo["filename"];


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
| VERIFY COMPRESSED FILE
|--------------------------------------------------------------------------
*/

$fullImagePath =
    dirname(__DIR__) .
    "/" .
    $imagePath;


if (!file_exists($fullImagePath)) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Compressed image was not created"
    ]);

    exit;
}


$finalImageSize =
    filesize($fullImagePath);


/*
|--------------------------------------------------------------------------
| MAKE SURE IMAGE IS <= 20 KB
|--------------------------------------------------------------------------
*/

if ($finalImageSize > 20 * 1024) {

    /*
    | Delete image if compression failed
    */

    unlink($fullImagePath);

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Could not compress image below 20 KB"
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
    | PRODUCT ID
    |--------------------------------------------------------------------------
    */

    $productId =
        $pdo->lastInsertId();


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        "success" =>
            true,

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
    |
    | Delete image because product was not inserted.
    |--------------------------------------------------------------------------
    */

    if (
        $imagePath &&
        file_exists($fullImagePath)
    ) {

        unlink($fullImagePath);
    }


    http_response_code(500);

    echo json_encode([

        "success" =>
            false,

        "message" =>
            "Could not add product"

    ]);

}

?>