<?php
require_once 'dbconfig.php';

function registerUser($name, $email, $password, $role) {
    global $pdo;
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPassword, $role]);
    return $pdo->lastInsertId();
}

function loginUser($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    return false;
}

function createJobPost($title, $description, $userId) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO job_posts (title, description, created_by) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $userId]);
}

function getJobPosts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM job_posts ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
