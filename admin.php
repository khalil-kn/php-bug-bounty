<?php
session_start();
require_once "connect.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM reports WHERE status = 'pending' ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h2>Pending Reports</h2>

<a href="logout.php">Logout</a>
<hr>

<?php if ($result->num_rows > 0): ?>

    <?php while ($report = $result->fetch_assoc()): ?>

        <div style="border:1px solid black; padding:10px; margin-bottom:15px;">
            <h3><?php echo htmlspecialchars($report["title"]); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($report["description"])); ?></p>
            <p><strong>Severity:</strong> <?php echo htmlspecialchars($report["severity"]); ?></p>
            <p><strong>Target:</strong> <?php echo htmlspecialchars($report["target_url"]); ?></p>

            <img src="uploads/<?php echo htmlspecialchars($report["screenshot"]); ?>" width="200">

            <br><br>

            <a href="approve.php?id=<?php echo $report["id"]; ?>">Approve</a> |
            <a href="delete.php?id=<?php echo $report["id"]; ?>">Delete</a>
        </div>

    <?php endwhile; ?>

<?php else: ?>
    <p>No pending reports.</p>
<?php endif; ?>

</body>
</html>