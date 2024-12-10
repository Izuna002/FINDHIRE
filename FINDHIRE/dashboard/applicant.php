<?php
require_once '../core/dbconfig.php';

if ($_SESSION['role'] !== 'applicant') {
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
    $job_post_id = $_POST['job_post_id'];
    $cover_letter = $_POST['cover_letter'];

    // File upload handling
    $resume = $_FILES['resume'];
    $target_dir = "../assets/uploads/";
    $resume_path = $target_dir . basename($resume['name']);
    if (move_uploaded_file($resume['tmp_name'], $resume_path)) {
        $stmt = $pdo->prepare("INSERT INTO applications (job_post_id, applicant_id, resume_path, cover_letter) VALUES (?, ?, ?, ?)");
        $stmt->execute([$job_post_id, $_SESSION['user_id'], $resume_path, $cover_letter]);
        echo "Application submitted successfully!";
    } else {
        echo "Failed to upload resume.";
    }
}

// Fetch all job posts
$stmt = $pdo->query("SELECT job_posts.*, users.name AS hr_name FROM job_posts JOIN users ON job_posts.created_by = users.id");
$job_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
</head>
<body>
    <h1>Available Job Posts</h1>
    <?php foreach ($job_posts as $post): ?>
        <form method="POST" enctype="multipart/form-data">
            <h3><?= htmlspecialchars($post['title']) ?></h3>
            <p><?= htmlspecialchars($post['description']) ?></p>
            <p>Posted by: <?= htmlspecialchars($post['hr_name']) ?></p>
            <textarea name="cover_letter" placeholder="Why are you a good fit?" required></textarea>
            <input type="hidden" name="job_post_id" value="<?= $post['id'] ?>">
            <input type="file" name="resume" accept=".pdf" required>
            <button type="submit" name="apply">Apply</button>
        </form>
    <?php endforeach; ?>

    <a href="javascript:history.back()" class="btn-back">⬅ Back</a>

</body>
</html>
