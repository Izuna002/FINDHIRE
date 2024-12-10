<?php
require_once 'core/dbconfig.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if the user is not logged in
    header("Location: login.php");
    exit();
}

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FindHire</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?= htmlspecialchars($user['name']) ?>!</h1>
        <p>Role: <?= htmlspecialchars($user['role']) ?></p>

        <!-- Navigation for HR -->
        <?php if ($user['role'] === 'HR'): ?>
            <h2>HR Dashboard</h2>
            <p>Manage job posts, view applications, and communicate with applicants.</p>
            <a href="dashboard/hr.php">Manage Job Posts</a><br>
            <a href="dashboard/view_applications.php">View Applications</a><br>
            <a href="dashboard/messages.php">Messages</a><br>
        <?php endif; ?>

        <!-- Navigation for Applicants -->
        <?php if ($user['role'] === 'applicant'): ?>
            <h2>Applicant Dashboard</h2>
            <p>Browse available job posts and apply, or check your messages.</p>
            <a href="dashboard/applicant.php">Browse Job Posts</a><br>
            <a href="dashboard/messages.php">Messages</a><br>
        <?php endif; ?>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
