<?php

header("Content-Type: application/json");

require_once "db.php";


if ($_SERVER["REQUEST_METHOD"] !== "GET") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Only GET requests are allowed"
    ]);

    exit;
}


try {

    $stmt = $pdo->query("
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
        ORDER BY id DESC
    ");


    $products = $stmt->fetchAll();


    echo json_encode([
        "success" => true,
        "products" => $products
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Could not load products"
    ]);
}?>