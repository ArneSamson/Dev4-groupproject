<?php

    include_once(__DIR__ . "/classes/User.php");
    
    session_start();
    if($_SESSION['loggedin'] !== true){
        header("Location: login.php");
    }