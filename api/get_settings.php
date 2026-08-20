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
    | Get nursery settings
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT
            nursery_name,
            address,
            phone,
            facebook_url,
            whatsapp_url
        FROM nursery_settings
        ORDER BY id ASC
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $settings = $stmt->fetch(PDO::FETCH_ASSOC);


    /*
    |--------------------------------------------------------------------------
    | No settings found
    |--------------------------------------------------------------------------
    */

    if (!$settings) {

        http_response_code(404);

        echo json_encode([
            "success" => false,
            "message" => "Nursery settings not found"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Success
    |--------------------------------------------------------------------------
    */

    echo json_encode([
        "success" => true,
        "settings" => [
            "nursery_name" =>
                $settings["nursery_name"] ?? "",

            "address" =>
                $settings["address"] ?? "",

            "phone" =>
                $settings["phone"] ?? "",

            "facebook_url" =>
                $settings["facebook_url"] ?? "",

            "whatsapp_url" =>
                $settings["whatsapp_url"] ?? ""
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);


} catch (PDOException $e) {

    error_log(
        "Get nursery settings error: " . $e->getMessage()
    );

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Could not load nursery settings"
    ]);
}?>