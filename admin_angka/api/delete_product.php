<?php

header("Content-Type: application/json");

require_once "db.php";


/* =========================================
   CHECK REQUEST
========================================= */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "POST request required."
    ]);

    exit;
}


/* =========================================
   GET ID
========================================= */

$id = isset($_POST["id"])
    ? (int) $_POST["id"]
    : 0;


if ($id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid product ID."
    ]);

    exit;
}


try {

    /* =========================================
       FIND PRODUCT
    ========================================= */

    $stmt = $pdo->prepare("
        SELECT *
        FROM plants
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->execute([$id]);

    $product = $stmt->fetch();


    if (!$product) {

        echo json_encode([
            "success" => false,
            "message" => "Product with ID $id not found."
        ]);

        exit;
    }


    /* =========================================
       DELETE DATABASE RECORD
    ========================================= */

    $delete = $pdo->prepare("
        DELETE FROM plants
        WHERE id = ?
    ");

    $delete->execute([$id]);


    /* =========================================
       CHECK DELETE
    ========================================= */

    if ($delete->rowCount() === 0) {

        throw new Exception(
            "Product could not be deleted from database."
        );
    }


    /* =========================================
       DELETE IMAGE
    ========================================= */

    if (!empty($product["image"])) {

        $image = $product["image"];


        /*
         * Remove URL/domain if database
         * contains a full URL.
         */

        $image = parse_url(
            $image,
            PHP_URL_PATH
        );


        /*
         * Convert image path to
         * local server path.
         */

        $imagePath =
            dirname(__DIR__) . "/" .
            ltrim($image, "/");


        /*
         * Delete image only if
         * it exists.
         */

        if (file_exists($imagePath)) {

            unlink($imagePath);

        }

    }


    /* =========================================
       SUCCESS
    ========================================= */

    echo json_encode([
        "success" => true,
        "message" => "Product deleted successfully."
    ]);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);

}?>