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

    $fileName = htmlspecialchars($_FILES["profile_picture"]["name"]);
    $fileTempName = $_FILES["profile_picture"]["tmp_name"];
    $fileSize = $_FILES["profile_picture"]["size"];
    $fileError = $_FILES["profile_picture"]["error"];

    if ($fileError !== UPLOAD_ERR_OK) {
        $message = "Upload failed with error code $fileError.";
        exit;
    }
    

    // Check file size
    if ($fileSize > 1000000) {
        $message = "File is too big"; // set error message
        header("Location: ../php/profile.php?error=" . urlencode($message)); // redirect to edit account page
        exit;
    }

    // Check file name for invalid characters
    if (!preg_match('/^[a-zA-Z0-9_]+\.[a-zA-Z0-9]{3,4}$/', $fileName)) {
        $message = "File name is not correct"; // set error message
        header("Location: ../php/profile.php?error=" . urlencode($message)); // redirect to edit account page
        exit;
    }

    // Save uploaded file to disk
    $uploadsDir = "..\\media\\";
    $fileName = basename($fileTempName);
    $filePath = $uploadsDir . $fileName;

    if (!move_uploaded_file($fileTempName, $filePath)) {
        $message = "Failed to move uploaded file.";
        exit;
    }

    try {
        $conn = Db::getInstance();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $statement = $conn->prepare("UPDATE users SET username = :username, email = :email, password = :password, role = :role, imagepath = :imagepath WHERE id = :id");
        $statement->bindValue(":username", $username);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $hash);
        $statement->bindValue(":role", $role);
        $statement->bindValue(":imagepath", $filePath);
        $statement->bindValue(":id", $user_id);
        $statement->execute();
        return true;
    } catch (Throwable $e) {
        $error = $e->getMessage();
        return false;
    }
}