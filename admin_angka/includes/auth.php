<?php
header("X-Robots-Tag: noindex, nofollow, noarchive");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| Check Admin Login
|--------------------------------------------------------------------------
*/

if (
    empty($_SESSION["admin_logged_in"]) ||
    $_SESSION["admin_logged_in"] !== true
) {

    header("Location: login.html");

    exit;
}?>