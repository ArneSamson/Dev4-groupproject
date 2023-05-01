<?php

class User {
    private $id;
    private $username;
    private $email;
    private $emailVerified;
    private $password;
    private $profilepictureUrl;
    private $savedPrompts;

    public function setUsername($p_susername){
        if(empty($p_susername))
            throw new Exception("Firstname cannot be empty");
        else{
            $this->username = $p_susername;
            }
    }

    public function setProfilePicture($p_sprofilepicture){
        if(empty($p_sprofilepicture))
            throw new Exception("Profile picture cannot be empty");
        else{
            $this->profilepictureUrl = $p_sprofilepicture;
            }
    }

    public function setPassword($p_spassword){
        if(empty($p_spassword))
            throw new Exception("Password cannot be empty");
        else{
            $this->password = $p_spassword;
            }
    }

    public function setEmail($p_semail){
        if(empty($p_semail))
            throw new Exception("Email cannot be empty");
        else{
            $this->email = $p_semail;
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

    public function setId(){
        $this->id = "placeholder";
    }

    public function getId() {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $statement->bindValue(":email", $this->email);
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
    
    public function save(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO users (username, email, password, profilepictureUrl) VALUES (:username, :email, :password, :profilepictureUrl)");
        $statement->bindValue(":username", $this->username);
        $statement->bindValue(":email", $this->email);
        $statement->bindValue(":password", $this->password);
        $statement->bindValue(":profilepictureUrl", $this->profilepictureUrl);
        $result = $statement->execute();
        return $result;
    }
    
    
}