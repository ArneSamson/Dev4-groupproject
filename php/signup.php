<?php

include_once(__DIR__ . '/bootstrap.php');

if (!empty($_POST)) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        // Create a new user object
        $user = new User($username, $email, $password);
        var_dump($user);

        // Register the user
        $result = $user->register();

        if ($result) {
            // Send verification email
            $emailVerification = new EmailVerification('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');
            $emailVerification->sendVerificationEmail($username, $email, $user->getVerificationCode());
            // We zetten hier de user rol in registratieproces
            $user->setRole('user');

            // Redirect to login page
            header("Location: login.php");
            exit();
        } else {
            throw new Exception('User registration failed.');
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
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
