<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false, 
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();require_once "connect.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin.php");
    exit();
}

if (!isset($_POST["csrf_token"]) || 
    !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])) {
    die("Invalid CSRF token");
}

$id = intval($_POST["id"]);

$stmt = $conn->prepare("UPDATE reports SET status = 'approved' WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin.php");
exit();