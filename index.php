<?php
require_once('connect.php');

// Prepare statement to get only approved reports
$stmt = $conn->prepare("SELECT id, title, description, severity, target_url, screenshot, created_at FROM reports WHERE status = ?");
$status = 'approved';
$stmt->bind_param("s", $status);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Approved Vulnerability Reports</title>
</head>
<body>

<h2>Approved Vulnerability Reports</h2>

<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div style='border:1px solid #ccc; padding:10px; margin-bottom:10px;'>";
        echo "<h3>" . htmlspecialchars($row['title']) . " [" . $row['severity'] . "]</h3>";
        echo "<p>" . nl2br(htmlspecialchars($row['description'])) . "</p>";
        echo "<p>Target URL: <a href='" . htmlspecialchars($row['target_url']) . "'>" . htmlspecialchars($row['target_url']) . "</a></p>";
        echo "<p>Submitted at: " . $row['created_at'] . "</p>";
        echo "<img src='" . htmlspecialchars($row['screenshot']) . "' alt='Screenshot' style='max-width:400px;'><br>";
        echo "</div>";
    }
} else {
    echo "<p>No reports approved yet.</p>";
}
?>

</body>
</html>