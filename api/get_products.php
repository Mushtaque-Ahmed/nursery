<?php

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../includes/confige.php";
require_once __DIR__ . "/../admin_angka/api/db.php";


/*
|--------------------------------------------------------------------------
| Only GET requests
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "GET") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Only GET requests are allowed"
    ]);

    exit;
}


try {

    /*
    |--------------------------------------------------------------------------
    | Get Nursery Phone
    |--------------------------------------------------------------------------
    */

    $settingsSql = "
        SELECT phone
        FROM nursery_settings
        ORDER BY id ASC
        LIMIT 1
    ";

    $settingsStmt =
        $pdo->prepare($settingsSql);

    $settingsStmt->execute();

    $settings =
        $settingsStmt->fetch(PDO::FETCH_ASSOC);


    $phone =
        !empty($settings["phone"])
            ? $settings["phone"]
            : "";


    /*
    |--------------------------------------------------------------------------
    | Get Available Products
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT
            id,
            name,
            category,
            price,
            sale_price,
            stock,
            description,
            image,
            available,
            featured
        FROM plants
        WHERE available = 1
        ORDER BY featured DESC, id DESC
        LIMIT 12
    ";


    $stmt =
        $pdo->prepare($sql);

    $stmt->execute();


    $products =
        $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );


    /*
    |--------------------------------------------------------------------------
    | Convert Database Values
    |--------------------------------------------------------------------------
    */

    foreach ($products as &$product) {

        $product["id"] =
            (int) $product["id"];


        $product["price"] =
            (float) $product["price"];


        $product["sale_price"] =
            $product["sale_price"] !== null
                ? (float) $product["sale_price"]
                : null;


        $product["stock"] =
            (int) $product["stock"];


        $product["available"] =
            (bool) $product["available"];


        $product["featured"] =
            (bool) $product["featured"];


        /*
        |--------------------------------------------------------------------------
        | Image URL
        |--------------------------------------------------------------------------
        */

        if (!empty($product["image"])) {

            $product["image"] =
                BASE_URL .
                "admin_angka/" .
                $product["image"];
        }

    }

    unset($product);


    /*
    |--------------------------------------------------------------------------
    | Success Response
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        "success" => true,

        "count" =>
            count($products),

        "phone" =>
            $phone,

        "products" =>
            $products

    ], JSON_UNESCAPED_SLASHES);


} catch (PDOException $e) {

    /*
    |--------------------------------------------------------------------------
    | Error Response
    |--------------------------------------------------------------------------
    */

    http_response_code(500);

    echo json_encode([

        "success" => false,

        "message" =>
            "Could not load products"

    ]);

}

?>