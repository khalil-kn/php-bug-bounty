<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();
require_once "connect.php";

/* Flash message handling */
if (!isset($_SESSION['flash'])) {
    $_SESSION['flash'] = [];
}

function set_flash($type, $message) {
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function display_flash() {
    if (!empty($_SESSION['flash'])) {
        foreach ($_SESSION['flash'] as $msg) {
            echo "<p style='color:" . ($msg['type'] === 'error' ? 'red' : 'green') . ";'>";
            echo htmlspecialchars($msg['message']);
            echo "</p>";
        }
        $_SESSION['flash'] = [];
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $severity = strtolower(trim($_POST["severity"]));
    $target_url = trim($_POST["target_url"]);

    /* ===== INPUT VALIDATION ===== */
    $valid = true;

    if (empty($title) || strlen($title) > 100) {
        set_flash('error', 'Title must be 1-100 characters.');
        $valid = false;
    }

    if (empty($description) || strlen($description) > 1000) {
        set_flash('error', 'Description must be 1-1000 characters.');
        $valid = false;
    }

    if (!in_array($severity, ['low','medium','high'])) {
        set_flash('error', 'Severity must be low, medium, or high.');
        $valid = false;
    }

    if (!filter_var($target_url, FILTER_VALIDATE_URL)) {
        set_flash('error', 'Target URL is invalid.');
        $valid = false;
    }

    /* ===== FILE UPLOAD ===== */
    $file = $_FILES["screenshot"] ?? null;

    if ($file && $file["error"] === UPLOAD_ERR_OK) {
        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($file["size"] > $maxSize) {
            set_flash('error', 'File too large. Max 2MB.');
            $valid = false;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file["tmp_name"]);
        $allowedTypes = [
            "image/jpeg" => "jpg",
            "image/png"  => "png",
            "image/gif"  => "gif"
        ];

        if (!array_key_exists($mimeType, $allowedTypes)) {
            set_flash('error', 'Invalid file type.');
            $valid = false;
        }

        if ($valid) {
            $extension = $allowedTypes[$mimeType];
            $newFileName = bin2hex(random_bytes(16)) . "." . $extension;
            $uploadPath = "uploads/" . $newFileName;
            if (!move_uploaded_file($file["tmp_name"], $uploadPath)) {
                set_flash('error', 'Failed to move uploaded file.');
                $valid = false;
            }
        }
    } else {
        set_flash('error', 'Screenshot file is required.');
        $valid = false;
    }

    /* ===== STORE IN DB ===== */
    if ($valid) {
        $stmt = $conn->prepare("INSERT INTO reports (title, description, severity, target_url, screenshot, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("sssss", $title, $description, $severity, $target_url, $newFileName);
        if ($stmt->execute()) {
            set_flash('success', 'Report submitted successfully!');
            header("Location: submit_report.php");
            exit();
        } else {
            set_flash('error', 'Database error. Try again.');
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Bug Report</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<h2>Submit Bug Report</h2>

<?php display_flash(); ?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title" required value="<?php echo htmlspecialchars($_POST['title'] ?? '') ?>"><br><br>
    <textarea name="description" placeholder="Description" required><?php echo htmlspecialchars($_POST['description'] ?? '') ?></textarea><br><br>
    <input type="text" name="severity" placeholder="Severity (low/medium/high)" required value="<?php echo htmlspecialchars($_POST['severity'] ?? '') ?>"><br><br>
    <input type="text" name="target_url" placeholder="Target URL" required value="<?php echo htmlspecialchars($_POST['target_url'] ?? '') ?>"><br><br>
    <input type="file" name="screenshot" required><br><br>
    <button type="submit">Submit</button>
</form>

</body>
</html>