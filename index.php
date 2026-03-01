<?php
require_once('connect.php');

$stmt = $conn->prepare("SELECT id, title, description, severity, target_url, screenshot, created_at FROM reports WHERE status = ? ORDER BY created_at DESC");
$status = 'approved';
$stmt->bind_param("s", $status);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Approved Reports | CyberWatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-red-950 text-gray-200">

<!-- NAVBAR -->
<nav class="bg-black/80 backdrop-blur-md border-b border-red-900/40">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="text-red-500 font-bold text-xl tracking-wider">
            CYBERWATCH
        </div>
        <div class="space-x-6 text-sm">
            <a href="index.php" class="hover:text-red-400 transition">Home</a>
            <a href="report.php" class="hover:text-red-400 transition">Submit Report</a>
            <a href="login.php" class="hover:text-red-400 transition">Admin</a>
        </div>
    </div>
</nav>

<div class="max-w-6xl mx-auto py-12 px-6">

    <h1 class="text-4xl font-bold text-red-500 mb-10 tracking-wide">
        Approved Vulnerability Reports
    </h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>

            <?php
                $severityColor = "bg-gray-700 text-gray-300";
                if ($row['severity'] === 'low') {
                    $severityColor = "bg-green-900 text-green-400";
                } elseif ($row['severity'] === 'medium') {
                    $severityColor = "bg-yellow-900 text-yellow-400";
                } elseif ($row['severity'] === 'high') {
                    $severityColor = "bg-red-900 text-red-400";
                } elseif ($row['severity'] === 'critical') {
                    $severityColor = "bg-red-800 text-red-300 border border-red-500";
                }
            ?>

            <div class="bg-gray-900/80 border border-red-900/40 rounded-2xl p-6 mb-8 shadow-lg hover:border-red-600 transition">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-red-400">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </h2>
                    <span class="px-3 py-1 text-sm rounded-full <?php echo $severityColor; ?>">
                        <?php echo strtoupper(htmlspecialchars($row['severity'])); ?>
                    </span>
                </div>

                <p class="text-gray-300 mb-4 leading-relaxed">
                    <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                </p>

                <p class="text-sm mb-2">
                    <span class="text-gray-500">Target:</span>
                    <a href="<?php echo htmlspecialchars($row['target_url']); ?>"
                       class="text-red-400 hover:underline break-all"
                       target="_blank">
                        <?php echo htmlspecialchars($row['target_url']); ?>
                    </a>
                </p>

                <p class="text-xs text-gray-500 mb-4">
                    Submitted at: <?php echo htmlspecialchars($row['created_at']); ?>
                </p>

                <?php if (!empty($row['screenshot'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['screenshot']); ?>"
                         class="rounded-xl border border-gray-700 max-w-md"
                         alt="Screenshot">
                <?php endif; ?>

            </div>

        <?php endwhile; ?>
    <?php else: ?>
        <div class="bg-gray-900/60 border border-gray-700 rounded-2xl p-6 text-center text-gray-400">
            No approved reports yet.
        </div>
    <?php endif; ?>

</div>

</body>
</html>