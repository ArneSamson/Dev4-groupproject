<?php

function canLogIn($p_username, $p_password) {
    try {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT id, password, verified, role FROM users WHERE username = :username");
        $statement->bindValue(":username", $p_username);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        if ($result !== false) {
            $hash = $result['password'];
            
            if (password_verify($p_password, $hash)) {
                if ($result['verified'] == 1) {
                    return [
                        'id' => $result['id'],
                        'role' => $result['role']
                    ];
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
    
    return false;
}

function updateUser($user_id, $username, $email, $password, $role, $conn)
{
    try {
        $conn = Db::getInstance();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $statement = $conn->prepare("UPDATE users SET username = :username, email = :email, password = :password, role = :role WHERE id = :id");
        $statement->bindValue(":username", $username);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $hash);
        $statement->bindValue(":role", $role);
        $statement->bindValue(":id", $user_id);
        $statement->execute();
        return true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
        return false;
    }
}