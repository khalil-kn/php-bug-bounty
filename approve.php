<?php
session_start();
require_once "connect.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {

    $id = intval($_GET["id"]);

    $stmt = $conn->prepare("UPDATE reports SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: admin.php");
exit();