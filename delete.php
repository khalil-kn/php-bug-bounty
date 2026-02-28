<?php
session_start();
require_once "connect.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {

    $id = intval($_GET["id"]);

    // First get screenshot filename
    $stmt = $conn->prepare("SELECT screenshot FROM reports WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $report = $result->fetch_assoc();

    if ($report) {
        unlink("uploads/" . $report["screenshot"]);
    }

    // Delete from DB
    $stmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: admin.php");
exit();