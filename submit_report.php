<?php
require_once('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $severity = $_POST['severity'];
    $target_url = trim($_POST['target_url']);

    //  Basic validation
    if (empty($title) || empty($description) || empty($severity) || empty($target_url)) {
        die("All fields are required.");
    }

    //  Validate image
    if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] == 0) {

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['screenshot']['tmp_name']);

        if (!in_array($file_type, $allowed_types)) {
            die("Invalid image type.");
        }

        //  Move uploaded file
        $upload_dir = "uploads/";
        $file_name = uniqid() . "_" . basename($_FILES['screenshot']['name']);
        $target_path = $upload_dir . $file_name;

        if (!move_uploaded_file($_FILES['screenshot']['tmp_name'], $target_path)) {
            die("Failed to upload image.");
        }

    } else {
        die("Image upload error.");
    }

    //  Insert into database 
    $stmt = $conn->prepare("INSERT INTO reports (title, description, severity, target_url, screenshot) VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param("sssss", $title, $description, $severity, $target_url, $target_path);

    if ($stmt->execute()) {
        echo "Report submitted successfully!";
    } else {
        echo "Database error.";
    }

    $stmt->close();
}
?>