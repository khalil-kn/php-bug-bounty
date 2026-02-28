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

/* Get screenshot filename */
$stmt = $conn->prepare("SELECT screenshot FROM reports WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$report = $result->fetch_assoc();

if ($report) {
    $filePath = "uploads/" . $report["screenshot"];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

/* Delete report from DB */
$stmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: admin.php");
exit();