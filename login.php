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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["admin_id"] = $user["id"];
            header("Location: admin.php");
            exit();

        } else {
            $error = "Invalid credentials";
        }

    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | CyberWatch</title>
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
        </div>
    </div>
</nav>

<!-- LOGIN CARD -->
<div class="flex items-center justify-center py-20 px-6">

    <div class="w-full max-w-md bg-gray-900/80 border border-red-900/40 rounded-2xl p-8 shadow-2xl">

        <h1 class="text-3xl font-bold text-red-500 mb-8 text-center">
            Admin Access
        </h1>

        <?php if (!empty($error)): ?>
            <div class="mb-6 bg-red-900/40 border border-red-700 text-red-300 px-4 py-3 rounded-lg text-sm">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">

            <div>
                <label class="block text-sm mb-2">Username</label>
                <input type="text" name="username" required
                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm mb-2">Password</label>
                <input type="password" name="password" required
                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none">
            </div>

            <button type="submit"
                class="w-full bg-red-700 hover:bg-red-800 py-3 rounded-lg font-semibold tracking-wide transition shadow-lg">
                Login
            </button>

        </form>

    </div>

</div>

</body>
</html>