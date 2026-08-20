<?php

header("Content-Type: application/json; charset=UTF-8");

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
    | Get available products
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
    ";


    $stmt = $pdo->prepare($sql);

    $stmt->execute();


    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /*
    |--------------------------------------------------------------------------
    | Convert database values to proper JSON types
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
                "admin_angka/" . $product["image"];

        }

    }

    unset($product);


    /*
    |--------------------------------------------------------------------------
    | Success response
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        "success" => true,

        "count" =>
            count($products),

        "products" =>
            $products

    ], JSON_UNESCAPED_SLASHES);


} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([

        "success" => false,

        "message" =>
            "Could not load products"

    ]);

}

?>