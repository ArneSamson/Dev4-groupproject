<?php

function canLogIn($p_username, $p_password) {
    try {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT id, password, verified FROM users WHERE username = :username");
        $statement->bindValue(":username", $p_username);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result !== false) {
            $hash = $result['password'];
            if (password_verify($p_password, $hash)) {
                if ($result['verified'] == 1) {
                    // Set the user ID in the session
                    $_SESSION['user_id'] = $result['id'];
                    var_dump($result['id']);
                    var_dump($_SESSION);
                    return $result['id'];
                   
                } else {
                    throw new Exception('User is not verified');
                }
            } else {
                throw new Exception('Invalid password');
            }
        } else {
            throw new Exception('Invalid username');
        }
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

function updateUser($user_id, $username, $email, $password, $conn)
{
    try {
        $conn = Db::getInstance();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $statement = $conn->prepare("UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id");
        $statement->bindValue(":username", $username);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $hash);
        $statement->bindValue(":id", $user_id);
        $statement->execute();
        return true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
        return false;
    }
}



