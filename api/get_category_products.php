<?php

header("Content-Type: application/json");

require_once __DIR__ . "/../admin_angka/api/db.php";


/*
|--------------------------------------------------------------------------
| Get category
|--------------------------------------------------------------------------
*/

$category = trim($_GET["category"] ?? "");


/*
|--------------------------------------------------------------------------
| Allowed categories
|--------------------------------------------------------------------------
|
| This prevents someone from sending arbitrary values.
|--------------------------------------------------------------------------
*/

$allowedCategories = [
    "indoor",
    "outdoor",
    "flowering",
    "garden"
];


if ($category === "") {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Category is required"
    ]);

    exit;
}


if (!in_array($category, $allowedCategories, true)) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Invalid category"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Get products
|--------------------------------------------------------------------------
*/

try {

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
            featured,
            created_at
        FROM plants
        WHERE category = :category
        ORDER BY id DESC
    ";


    $stmt = $pdo->prepare($sql);


    $stmt->execute([
        ":category" => $category
    ]);


    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /*
    |--------------------------------------------------------------------------
    | Convert database values
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
            
              if (!empty($product["image"])) {

            $product["image"] =
                "admin_angka/" . $product["image"];

        }
    }

    unset($product);


    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        "success" => true,

        "category" => $category,

        "count" => count($products),

        "products" => $products

    ]);


} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([

        "success" => false,

        "message" => "Could not load category products"

    ]);

}

?>