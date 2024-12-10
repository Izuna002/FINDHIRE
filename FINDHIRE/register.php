<?php
require_once 'core/handleforms.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role">
            <option value="applicant">Applicant</option>
            <option value="HR">HR</option>
        </select>
        <button type="submit" name="register">Register</button>
    </form>

<a href="javascript:history.back()" class="btn-back">⬅ Back</a>

</body>
</html>
