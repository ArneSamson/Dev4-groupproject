<?php

    include_once(__DIR__ . "/classes/User.php");

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>