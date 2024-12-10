<?php
require_once '../core/dbconfig.php';

if ($_SESSION['role'] !== 'HR') {
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];
    $action = $_POST['action'];

    // Determine status based on the action
    $status = $action === 'accept' ? 'accepted' : 'rejected';

    // Update application status
    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->execute([$status, $application_id]);

    if ($stmt->rowCount() > 0) {
        // Send a message to the applicant
        $stmt = $pdo->prepare("
            SELECT applicant_id FROM applications WHERE id = ?
        ");
        $stmt->execute([$application_id]);
        $applicant_id = $stmt->fetchColumn();

        $message = $status === 'accepted'
            ? "Congratulations, your application has been accepted!"
            : "We regret to inform you that your application has been rejected.";

        $stmt = $pdo->prepare("
            INSERT INTO messages (sender_id, receiver_id, message) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $applicant_id, $message]);

        echo 'success';
    } else {
        echo 'error';
    }
}
?>
