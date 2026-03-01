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

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$stmt = $conn->prepare("SELECT * FROM reports WHERE status = 'pending' ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | CyberWatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-red-950 text-gray-200">

<!-- NAVBAR -->
<nav class="bg-black/80 backdrop-blur-md border-b border-red-900/40">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="text-red-500 font-bold text-xl tracking-wider">
            SECURITY CONTROL PANEL
        </div>
        <div class="space-x-6 text-sm">
            <a href="admin.php" class="hover:text-red-400 transition">Dashboard</a>
            <a href="index.php" class="hover:text-red-400 transition">Public View</a>
            <a href="logout.php" class="text-red-500 hover:text-red-400 transition">Logout</a>
        </div>
    </div>
</nav>

<div class="max-w-6xl mx-auto py-12 px-6">

    <h1 class="text-4xl font-bold text-red-500 mb-10">
        Pending Reports
    </h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($report = $result->fetch_assoc()): ?>

            <div class="bg-gray-900/80 border border-red-900/40 rounded-2xl p-6 mb-8 shadow-lg">

                <h2 class="text-xl text-red-400 font-semibold mb-3">
                    <?php echo htmlspecialchars($report["title"]); ?>
                </h2>

                <p class="text-gray-300 mb-4">
                    <?php echo nl2br(htmlspecialchars($report["description"])); ?>
                </p>

                <p class="text-sm mb-2">
                    <span class="text-gray-500">Severity:</span>
                    <?php echo strtoupper(htmlspecialchars($report["severity"])); ?>
                </p>

                <p class="text-sm mb-4">
                    <span class="text-gray-500">Target:</span>
                    <?php echo htmlspecialchars($report["target_url"]); ?>
                </p>

                <?php if (!empty($report["screenshot"])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($report["screenshot"]); ?>"
                         class="rounded-xl border border-gray-700 max-w-xs mb-4">
                <?php endif; ?>

                <div class="flex gap-4">

                    <form method="POST" action="approve.php">
                        <input type="hidden" name="id" value="<?php echo $report["id"]; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
                        <button type="submit"
                            class="bg-green-700 hover:bg-green-800 px-4 py-2 rounded-lg transition">
                            ✔ Approve
                        </button>
                    </form>

                    <form method="POST" action="delete.php">
                        <input type="hidden" name="id" value="<?php echo $report["id"]; ?>">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
                        <button type="submit"
                            class="bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg transition">
                            ✖ Delete
                        </button>
                    </form>

                </div>

            </div>

        <?php endwhile; ?>
    <?php else: ?>
        <div class="bg-gray-900/60 border border-gray-700 rounded-2xl p-6 text-center text-gray-400">
            No pending reports.
        </div>
    <?php endif; ?>

</div>

</body>
</html>