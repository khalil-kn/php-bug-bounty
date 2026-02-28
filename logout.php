<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

$_SESSION = [];
session_destroy();

header("Location: login.php");
exit();