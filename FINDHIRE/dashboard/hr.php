<?php
require_once '../core/dbconfig.php';

if ($_SESSION['role'] !== 'HR') {
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_job'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $stmt = $pdo->prepare("INSERT INTO job_posts (title, description, created_by) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $_SESSION['user_id']]);
    echo "Job post created successfully!";
}

// Fetch all job posts created by the logged-in HR
$stmt = $pdo->prepare("SELECT * FROM job_posts WHERE created_by = ?");
$stmt->execute([$_SESSION['user_id']]);
$job_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Job Management</title>
</head>
<body>
    <h1>Post a Job</h1>
    <form method="POST">
        <input type="text" name="title" placeholder="Job Title" required>
        <textarea name="description" placeholder="Job Description" required></textarea>
        <button type="submit" name="post_job">Post Job</button>
    </form>

    <h2>My Job Posts</h2>
    <?php foreach ($job_posts as $post): ?>
        <p><strong><?= htmlspecialchars($post['title']) ?></strong>: <?= htmlspecialchars($post['description']) ?></p>
    <?php endforeach; ?>

    <a href="javascript:history.back()" class="btn-back">⬅ Back</a>

</body>
</html>
