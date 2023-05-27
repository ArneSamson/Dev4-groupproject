<?php

include_once("../php/bootstrap.php");

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

function deleteAccount($deleteId){

    $id = $deleteId;
    $conn = Db::getInstance();
    $statement = $conn->prepare("DELETE FROM users WHERE id = :id");
    $statement->bindValue(":id", $deleteId);
    $statement->execute();
    session_destroy();
    header("Location: ../php/login.php");
    exit();

}

function getPromptFromURL($idFromURL) {
    $promptId = $idFromURL;

    // Get the prompt details based on the prompt ID
    $prompt = Prompts::getPromptById($promptId);

    $user = User::getById($prompt['user_id']);


    // Check if the user exists
    if ($user === null) {
        header("Location: prompts.php");
        exit();
    }

    //function to get the word count of a description of the prompt
    function wordCount($string) {
        $string = strip_tags($string);
        $string = preg_replace('/\s+/', ' ', $string);
        $words = explode(" ", $string);
        return count($words);
    }

    $isCurrentUserPrompt = false;
    if ($_SESSION['user_id'] === $prompt['user_id']) {
        $isCurrentUserPrompt = true;
    }

    return array('prompt' => $prompt, 'user' => $user, 'isCurrentUserPrompt' => $isCurrentUserPrompt);

}