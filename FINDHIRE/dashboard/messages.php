<?php
require_once '../core/dbconfig.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get user ID
$current_user_id = $_SESSION['user_id'];

// Fetch messages where the user is either the sender or receiver
$stmt = $pdo->prepare("
    SELECT 
        m.*, 
        s.name AS sender_name, 
        r.name AS receiver_name 
    FROM messages m
    JOIN users s ON m.sender_id = s.id
    JOIN users r ON m.receiver_id = r.id
    WHERE m.sender_id = ? OR m.receiver_id = ?
    ORDER BY m.sent_at DESC
");
$stmt->execute([$current_user_id, $current_user_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - FindHire</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Messages</h1>

        <?php if (!empty($messages)): ?>
            <ul>
                <?php foreach ($messages as $message): ?>
                    <li>
                        <strong>From:</strong> <?= htmlspecialchars($message['sender_name']) ?> 
                        <strong>To:</strong> <?= htmlspecialchars($message['receiver_name']) ?><br>
                        <strong>Message:</strong> <?= htmlspecialchars($message['message']) ?><br>
                        <em>Sent at: <?= htmlspecialchars($message['sent_at']) ?></em>
                    </li>
                    <hr>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No messages found.</p>
        <?php endif; ?>

        <a href="../dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
