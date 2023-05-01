<?php

include_once(__DIR__ . '/bootstrap.php');

if(!empty($_POST)){

    $username = $_POST["username"];
    $email = $_POST["email"];
  
    $options = [
        'cost' => 12,
    ];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT, $options);

    try{
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $statement->bindValue(":username", $username); //SQL injection protection
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $password);
        $statement->execute();

        // Redirect to login page
        header("Location: login.php");
        exit();
       
       
    }
    catch(Throwable $e){
        $error = "Error sending verification email";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>AI Prompt Home</title>
    <link rel="stylesheet" type="text/css" href="css/home.css">
</head>
<body>
    <nav>
        <div class="logo">
            <img src="img/logo.png">
        </div>
        <div class="nav-buttons">
            <div class="credit-counter">
                Credits: 0
            </div>
            <div class="profile-button">
                <a href="#">Profile</a>
            </div>
            <div class="logout-button">
                <a href="#">Logout</a>
            </div>
        </div>
    </nav>

    <div class="upload-button">
        <a href="#" class="button-gradient">Upload Prompt</a>
    </div>

</body>
</html>

