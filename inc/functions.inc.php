<?php

function canLogIn($p_username, $p_password){
    try{
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $statement->bindValue(":username", $p_username);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result !== false) {
            $hash = $result['password'];
            if(password_verify($p_password, $hash)){
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
    } catch(Throwable $e){
        $error = $e->getMessage();
    }
}
?>