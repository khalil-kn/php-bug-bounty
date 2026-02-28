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

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

/* Generate CSRF token once per session */
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
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

        <div style="border:1px solid black; padding:15px; margin-bottom:15px;">

            <h3><?php echo htmlspecialchars($report["title"]); ?></h3>

            <p><?php echo nl2br(htmlspecialchars($report["description"])); ?></p>

            <p><strong>Severity:</strong>
                <?php echo htmlspecialchars($report["severity"]); ?>
            </p>

            <p><strong>Target:</strong>
                <?php echo htmlspecialchars($report["target_url"]); ?>
            </p>

            <img src="uploads/<?php echo htmlspecialchars($report["screenshot"]); ?>" width="200">

            <br><br>

            <!-- Approve Form -->
            <form method="POST" action="approve.php" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $report["id"]; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
                <button type="submit">Approve</button>
            </form>

            <!-- Delete Form -->
            <form method="POST" action="delete.php" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $report["id"]; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
                <button type="submit">Delete</button>
            </form>

        </div>

    <?php endwhile; ?>

<?php else: ?>
    <p>No pending reports.</p>
<?php endif; ?>

</body>
</html>