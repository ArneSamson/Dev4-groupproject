<?php
session_start();
require_once 'bootstrap.php';
include_once("../inc/functions.inc.php");

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if(!empty($_POST)){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user_id = canLogIn($username, $password);

    if ($user_id !== false) {
        $_SESSION['user_id'] = $user_id;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form">
        <h2 class="form__title">Login</h2>

        <?php if(isset($error)): ?>
            <div class="form__error">
                <p>Invalid username or password</p>
            </div>
        <?php elseif(isset($unverified)): ?>
            <div class="form__error">
                <p>Please verify your email address</p>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form__field">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form__field">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form__field">
                <input type="submit" value="Log in" class="form__button">
            </div>
        </form>
    </div>
</body>
</html>
