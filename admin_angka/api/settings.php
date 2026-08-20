<?php

header("Content-Type: application/json; charset=UTF-8");

require_once  "db.php";


/*
|--------------------------------------------------------------------------
| Only POST requests
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
| Get JSON input
|--------------------------------------------------------------------------
*/

$data = json_decode(file_get_contents("php://input"), true);


if (!is_array($data)) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid request data"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Sanitize input
|--------------------------------------------------------------------------
*/

$nurseryName = trim($data["nursery_name"] ?? "");
$address     = trim($data["address"] ?? "");
$phone       = trim($data["phone"] ?? "");
$facebookUrl = trim($data["facebook_url"] ?? "");
$whatsappUrl = trim($data["whatsapp_url"] ?? "");


/*
|--------------------------------------------------------------------------
| Validate nursery name
|--------------------------------------------------------------------------
*/

if ($nurseryName === "") {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Nursery name is required"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Validate Facebook URL
|--------------------------------------------------------------------------
*/

if ($facebookUrl !== "" && !filter_var($facebookUrl, FILTER_VALIDATE_URL)) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Invalid Facebook URL"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Validate WhatsApp URL
|--------------------------------------------------------------------------
*/

if ($whatsappUrl !== "" && !filter_var($whatsappUrl, FILTER_VALIDATE_URL)) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Invalid WhatsApp URL"
    ]);

    exit;
}


try {

    /*
    |--------------------------------------------------------------------------
    | Check settings
    |--------------------------------------------------------------------------
    */

    $check = $pdo->query(
        "SELECT id FROM nursery_settings ORDER BY id ASC LIMIT 1"
    );

    $existing = $check->fetch(PDO::FETCH_ASSOC);


    if ($existing) {

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $sql = "
            UPDATE nursery_settings
            SET
                nursery_name = :nursery_name,
                address = :address,
                phone = :phone,
                facebook_url = :facebook_url,
                whatsapp_url = :whatsapp_url
            WHERE id = :id
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nursery_name" => $nurseryName,
            ":address" => $address,
            ":phone" => $phone,
            ":facebook_url" => $facebookUrl,
            ":whatsapp_url" => $whatsappUrl,
            ":id" => $existing["id"]
        ]);

    } else {

        /*
        |--------------------------------------------------------------------------
        | Insert
        |--------------------------------------------------------------------------
        */

        $sql = "
            INSERT INTO nursery_settings
            (
                nursery_name,
                address,
                phone,
                facebook_url,
                whatsapp_url
            )
            VALUES
            (
                :nursery_name,
                :address,
                :phone,
                :facebook_url,
                :whatsapp_url
            )
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nursery_name" => $nurseryName,
            ":address" => $address,
            ":phone" => $phone,
            ":facebook_url" => $facebookUrl,
            ":whatsapp_url" => $whatsappUrl
        ]);
    }


    echo json_encode([
        "success" => true,
        "message" => "Nursery settings saved successfully"
    ]);


} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Could not save nursery settings"
    ]);
}?>