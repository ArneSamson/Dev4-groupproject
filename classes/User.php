<?php

class User {
    private $id;
    private $username;
    private $email;
    private $emailVerified;
    private $password;
    private $profilepictureUrl;
    private $savedPrompts;
    private $verificationCode;


    public function __construct($username, $email, $password) {
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setPassword($password);
    }
    
    public function setUsername($username) {
        if (empty($username)) {
            throw new Exception("Username cannot be empty");
        }
        
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            throw new Exception("Username already taken");
        }
        
        $this->username = $username;
    }

    public function setProfilePicture($profilepictureUrl) {
        if(empty($profilepictureUrl))
            throw new Exception("Profile picture cannot be empty");
        else{
            $this->profilepictureUrl = $profilepictureUrl;
        }
    }

    public function setPassword($password) {
        if(empty($password))
            throw new Exception("Password cannot be empty");
        else{
            $this->password = $password;
        }
    }

    public function setEmail($email) {
        if(empty($email))
            throw new Exception("Email cannot be empty");
        else{
            $this->email = $email;
        }
    }

    public function setEmailVerified($emailVerified) {
        $this->emailVerified = $emailVerified;
    }

    public function setSavedPrompts($savedPrompts) {
        $this->savedPrompts = $savedPrompts;
    }

    public function isEmailVerified() {
        return $this->emailVerified;
    }

    public function getId() {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $statement->bindValue(":username", $this->username);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->id = $result['id'];
        return $this->id;
    }

    public function getUsername() {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT username FROM users WHERE email = :email");
        $statement->bindValue(":email", $this->email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->username = $result['username'];
        return $this->username;
    }

    public function getEmail() {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT email FROM users WHERE email = :email");
        $statement->bindValue(":email", $this->email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->email = $result['email'];
        return $this->email;
    }

    public function getSavedPrompts() {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT savedPrompts FROM users WHERE email = :email");
        $statement->bindValue(":email", $this->email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $this->savedPrompts = $result['savedPrompts'];
        return $this->savedPrompts;
    }
    
    public static function getById($id) {
        // code to retrieve the user from the database based on their id, including emailVerified and profilePictureUrl
        
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    public static function getByEmail($email) {
        // code to retrieve the user from the database based on their email, including emailVerified and profilePictureUrl
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
        
    }
    
    public function register(){

        $verificationCode = bin2hex(random_bytes(32));

        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO users (username, email, password, email_verification) VALUES (:username, :email, :password, :emailVerification)");
        $statement->bindValue(":username", $this->username);
        $statement->bindValue(":email", $this->email);
        $statement->bindValue(":password", $this->password);
        $statement->bindValue(":emailVerification", $verificationCode);
        $result = $statement->execute();
        return $result;
    }
    
    
}