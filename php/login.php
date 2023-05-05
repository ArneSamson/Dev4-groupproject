<?php
session_start();
require_once 'bootstrap.php';
include_once("../inc/functions.inc.php");

//check form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = User::getByUsername($username);
    $_SESSION["user_id"] = $user['id'];
    
    if (!$user) {
        $errorMessage = "Incorrect username or password.";
    } else {
        if (password_verify($password, $user['password'])) {
            session_start();
            header('Location: index.php');
            var_dump($user['id']);
            exit();
        } else {
            $errorMessage = "Incorrect email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php if (isset($errorMessage)): ?>
        <p><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="username">username:</label>
        <input type="username" name="username" id="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
