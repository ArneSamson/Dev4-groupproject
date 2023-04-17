<?php
    require_once 'bootstrap.php';

    include_once("../inc/functions.inc.php");

    if(!empty($_POST)){
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(canLogIn($username, $password)){
            session_start();
            $_SESSION['loggedin'] = true;
            header("Location: index.php");
        }else{
            $error = true;
        }
    };
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <?php if(isset($error)): ?>
        <p>Invalid username or password</p>
    <?php endif; ?>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" value="Log in">
    </form>
</body>
</html>