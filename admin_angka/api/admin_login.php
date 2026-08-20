<?php

header("Content-Type: application/json; charset=UTF-8");

session_start();


/*
|--------------------------------------------------------------------------
| Load configuration and database
|--------------------------------------------------------------------------
*/


require_once "db.php";


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

$data = json_decode(
    file_get_contents("php://input"),
    true
);


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
| Get login credentials
|--------------------------------------------------------------------------
*/

$email = trim($data["email"] ?? "");
$password = $data["password"] ?? "";


/*
|--------------------------------------------------------------------------
| Validate input
|--------------------------------------------------------------------------
*/

if ($email === "" || $password === "") {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Email and password are required"
    ]);

    exit;
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    http_response_code(422);

    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid email address"
    ]);

    exit;
}


try {

    /*
    |--------------------------------------------------------------------------
    | Find admin
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT
            id,
            name,
            email,
            password,
            status
        FROM admins
        WHERE email = :email
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":email" => $email
    ]);

    $admin = $stmt->fetch(PDO::FETCH_ASSOC);


    /*
    |--------------------------------------------------------------------------
    | Check account and password
    |--------------------------------------------------------------------------
    */

    if (
        !$admin ||
        !password_verify($password, $admin["password"])
    ) {

        http_response_code(401);

        echo json_encode([
            "success" => false,
            "message" => "Invalid email or password"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Check account status
    |--------------------------------------------------------------------------
    */

    if ((int) $admin["status"] !== 1) {

        http_response_code(403);

        echo json_encode([
            "success" => false,
            "message" => "Your admin account is inactive"
        ]);

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Regenerate session ID
    |--------------------------------------------------------------------------
    */

    session_regenerate_id(true);


    /*
    |--------------------------------------------------------------------------
    | Store admin session
    |--------------------------------------------------------------------------
    */

    $_SESSION["admin_logged_in"] = true;

    $_SESSION["admin_id"] =
        (int) $admin["id"];

    $_SESSION["admin_name"] =
        $admin["name"];

    $_SESSION["admin_email"] =
        $admin["email"];


    /*
    |--------------------------------------------------------------------------
    | Successful login
    |--------------------------------------------------------------------------
    */

    echo json_encode([
        "success" => true,
        "message" => "Login successful",
        "redirect" => "admin/dashboard.php"
    ]);


} catch (PDOException $e) {

    /*
    |--------------------------------------------------------------------------
    | Do not expose database error details
    |--------------------------------------------------------------------------
    */

    error_log(
        "Admin login error: " . $e->getMessage()
    );

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Unable to process login. Please try again."
    ]);

}

?>