<?php
require_once '../core/dbconfig.php';

if ($_SESSION['role'] !== 'HR') {
    header("Location: ../dashboard.php");
    exit();
}

// Fetch all pending applications
$stmt = $pdo->prepare("
    SELECT a.*, u.name AS applicant_name, jp.title AS job_title 
    FROM applications a
    JOIN users u ON a.applicant_id = u.id
    JOIN job_posts jp ON a.job_post_id = jp.id
    WHERE a.status = 'pending'
    ORDER BY a.applied_at DESC
");
$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applications</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script>
        function handleAction(applicationId, action) {
            if (confirm(`Are you sure you want to ${action} this application?`)) {
                fetch('update_application.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `application_id=${applicationId}&action=${action}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        // Remove the application row dynamically
                        document.getElementById(`application-${applicationId}`).remove();
                    } else {
                        alert('Error processing application. Please try again.');
                    }
                });
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>View Applications</h1>
        <?php if (!empty($applications)): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Job Title</th>
                        <th>Cover Letter</th>
                        <th>Resume</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr id="application-<?= $application['id'] ?>">
                            <td><?= htmlspecialchars($application['applicant_name']) ?></td>
                            <td><?= htmlspecialchars($application['job_title']) ?></td>
                            <td><?= htmlspecialchars($application['cover_letter']) ?></td>
                            <td><a href="../uploads/<?= htmlspecialchars($application['resume_path']) ?>" target="_blank">View Resume</a></td>
                            <td>
                                <button onclick="handleAction(<?= $application['id'] ?>, 'accept')">Accept</button>
                                <button onclick="handleAction(<?= $application['id'] ?>, 'reject')">Reject</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending applications found.</p>
        <?php endif; ?>

        <a href="../dashboard.php" class="btn-back">⬅ Back</a>
    </div>
</body>
</html>
