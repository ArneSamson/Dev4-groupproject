<?php

function canLogIn($p_username, $p_password){

    try{
        $conn = new PDO("mysql:host=com-linweb551.srv.combell-ops.net:3306;dbname=ID378949_dev4LLA", "ID378949_user", "l7Og55u74e801mN990rU");
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
    }
    catch(Throwable $e){
        $error = $e->getMessage();
    }
}

?>

