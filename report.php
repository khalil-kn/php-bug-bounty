<?php
require_once('connect.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Vulnerability</title>
</head>
<body>

<h2>Submit Vulnerability Report</h2>

<form action="submit_report.php" method="POST" enctype="multipart/form-data">

    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required></textarea><br><br>

    <label>Severity:</label><br>
    <select name="severity" required>
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
        <option value="critical">Critical</option>
    </select><br><br>

    <label>Target URL:</label><br>
    <input type="url" name="target_url" required><br><br>

    <label>Screenshot:</label><br>
    <input type="file" name="screenshot" accept="image/*" required><br><br>

    <button type="submit">Submit Report</button>

</form>

</body>
</html>