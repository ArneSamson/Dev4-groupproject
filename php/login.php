<?php
require_once 'bootstrap.php';
include_once("../inc/functions.inc.php");

// Check form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = htmlspecialchars($_POST["username"], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST["password"], ENT_QUOTES, 'UTF-8');


    $loginResult = canLogIn($username, $password);
    
    if ($loginResult !== false) {
        session_start();
        $_SESSION['user_id'] = $loginResult['id'];
        $_SESSION['role'] = $loginResult['role'];
        header('Location: ../index.php');
        exit();
    } else {
        $errorMessage = "Incorrect username or password.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../media/favicon/icon.png" type="image/x-icon"/>

</head>
<body>
    <div class="form">
        <h2 class="form__title">Login</h2>

        <?php if (isset($errorMessage)): ?>
            <div class="form__error">
                <p><?php echo $errorMessage; ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form__field">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>

            <div class="form__field">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>

            <div class="form__field">
                <input type="submit" value="Login" class="form__button">
            </div>
        </form>
    </div>
</body>
</html>
