<?php
require_once 'models.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        if (registerUser($name, $email, $password, $role)) {
            header("Location: login.php");
        } else {
            echo "Registration failed.";
        }
    }

    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (loginUser($email, $password)) {
            header("Location: dashboard.php");
        } else {
            echo "Invalid login credentials.";
        }
    }
}
?>
