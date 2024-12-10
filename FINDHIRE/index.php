<?php
require_once 'core/dbconfig.php';

if (isset($_SESSION['user_id'])) {
    // Redirect logged-in users to the dashboard
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>FindHire - Welcome</title>
</head>
<body>
    <div class="container">
        <h1>Welcome to FindHire</h1>
        <p>The premier platform for managing job applications!</p>
        <p><a href="login.php">Login</a> or <a href="register.php">Register</a> to get started.</p>
    </div>
</body>
</html>
