<?php

if(!empty($_POST)){

    $username = $_POST["username"];

    $options = [
        'cost' => 12,
    ];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT, $options);


    try{
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $statement->bindValue(":username", $username); //SQL injection protection
        $statement->bindValue(":password", $password);
        $statement->execute();
        header("Location: login.php");

    }
    catch(Throwable $e){
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>IMDFlix</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="netflixLogin">
        <div class="form form--login">
            <form action="" method="post">
                <h2 form__title>Sign up</h2>

                <?php if( isset($error) ) : ?>
                    <div class="form__error">
                        <p>
                            Sorry, we can't log you in with that email address and password. Can you try again?
                        </p>
                    </div>
                <?php endif; ?>

                <div class="form__field">
                    <label for="Username">Username</label>
                    <input type="text" name="username">
                </div>
                <div class="form__field">
                    <label for="Password">Password</label>
                    <input type="password" name="password">
                </div>

                <div class="form__field">
                    <input type="submit" value="Sign up" class="btn btn--primary">    
                    <!-- <input type="checkbox" id="rememberMe"><label for="rememberMe" class="label__inline">Remember me</label> -->
                </div>
            </form>
        </div>
    </div>
</body>
</html>