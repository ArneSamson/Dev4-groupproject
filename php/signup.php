<?php

include_once(__DIR__ . '/bootstrap.php');
require_once(__DIR__ . '/../vendor/autoload.php');

use SendGrid\Mail\Mail;

if (!empty($_POST)) {
    $username = $_POST["username"];
    $email = $_POST["email"];
  
    $options = [
        'cost' => 12,
    ];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT, $options);

    try {
        // Generate email verification code
        $verificationCode = bin2hex(random_bytes(32));

        // Insert user data into database
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO users (username, email, password, email_verification) VALUES (:username, :email, :password, :emailVerification)");
        $statement->bindValue(":username", $username);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $password);
        $statement->bindValue(":emailVerification", $verificationCode);
        $statement->execute();
        var_dump($statement);

        // Send verification email
        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("ladiske@hotmail.com", "Ladis");
        $email->setSubject("Verify your email address");
        $email->addTo($email, $username);
        $email->addContent("text/plain", "Thank you for signing up! Please click on the following link to verify your email address: http://your-website.com/verify.php?code=$verificationCode");
        $email->addContent("text/html", "<p>Thank you for signing up!</p><p>Please click on the following link to verify your email address: <a href='http://your-website.com/verify.php?code=$verificationCode'>http://your-website.com/verify.php?code=$verificationCode</a></p>");
        $apiKey = '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';
        $sendgrid = new \SendGrid($apiKey);
        $response = $sendgrid->send($email);

        // Redirect to login page
        header("Location: login.php");
        exit();
    } catch (Throwable $e) {
        $error = "Error sending verification email";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form">
        <h2 class="form__title">Sign up</h2>

        <?php if( isset($error) ) : ?>
            <div class="form__error">
                <p>
                    <?php echo $error; ?>
                </p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form__field">
                <label for="username">Username</label>
                <input type="text" name="username" id="username">
            </div>

            <div class="form__field">
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
            </div>

            <div class="form__field">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </div>

            <div class="form__field">
                <input type="submit" value="Sign up" class="form__button">
            </div>
        </form>
    </div>
</body>
</html>
