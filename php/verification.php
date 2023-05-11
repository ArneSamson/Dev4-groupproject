<?php
include_once(__DIR__ . '/bootstrap.php');
require_once(__DIR__ . '/../vendor/autoload.php');

if (!empty($_GET['verification_code'])) {
    $verificationCode = $_GET['verification_code'];

    // Verify the user
    if (User::verifyUser($verificationCode)) {
        // User successfully verified
        header("Location: login.php");
        exit();
    } else {
        $error = "Invalid verification code";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>
<body>
    <h1>Email Verification</h1>
    <?php if (isset($error)) { ?>
        <p><?php echo $error; ?></p>
    <?php } ?>
    <form method="post" action="verification.php">
        <label for="verification_code">Enter verification code:</label>
        <input type="text" id="verification_code" name="verification_code">
        <br>
        <button type="submit">Verify Email</button>
    </form>
</body>
</html>