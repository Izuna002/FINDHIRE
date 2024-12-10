<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$dbname = "findhire";
$dsn = "mysql:host={$host};dbname={$dbname}";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone = '+08:00';");
    date_default_timezone_set('Asia/Manila');
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
