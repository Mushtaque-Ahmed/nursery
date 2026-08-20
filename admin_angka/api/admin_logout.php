<?php

session_start();


/*
|--------------------------------------------------------------------------
| Clear Session
|--------------------------------------------------------------------------
*/

$_SESSION = [];


/*
|--------------------------------------------------------------------------
| Destroy Session Cookie
|--------------------------------------------------------------------------
*/

if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}


/*
|--------------------------------------------------------------------------
| Destroy Session
|--------------------------------------------------------------------------
*/

session_destroy();


/*
|--------------------------------------------------------------------------
| Response
|--------------------------------------------------------------------------
*/

header("Content-Type: application/json; charset=UTF-8");

echo json_encode([
    "success" => true,
    "message" => "Logged out successfully"
]);

exit;?>