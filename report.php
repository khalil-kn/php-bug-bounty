<?php
require_once('connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Report | CyberWatch</title>
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

<div class="flex items-center justify-center py-16 px-6">

    <div class="w-full max-w-2xl bg-gray-900/80 border border-red-900/40 rounded-2xl p-8 shadow-2xl">

        <h1 class="text-3xl font-bold text-red-500 mb-8 text-center">
            Submit Vulnerability Report
        </h1>

        <form action="submit_report.php" method="POST" enctype="multipart/form-data" class="space-y-6">

            <div>
                <label class="block text-sm mb-2">Title</label>
                <input type="text" name="title" required
                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm mb-2">Description</label>
                <textarea name="description" required rows="4"
                          class="w-full bg-black border border-gray-700 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-sm mb-2">Severity</label>
                <select name="severity" required
                        class="w-full bg-black border border-gray-700 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-2">Target URL</label>
                <input type="url" name="target_url" required
                       class="w-full bg-black border border-gray-700 rounded-lg px-4 py-2 focus:border-red-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm mb-2">Screenshot</label>
                <input type="file" name="screenshot" accept="image/*" required
                       class="w-full text-sm text-gray-400">
            </div>

            <button type="submit"
                class="w-full bg-red-700 hover:bg-red-800 py-3 rounded-lg font-semibold tracking-wide transition shadow-lg">
                Submit Report
            </button>

        </form>

    </div>

</div>

</body>
</html>