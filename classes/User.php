<?php

class User {
    private $id;
    private string $username;
    private string $email;
    private bool $emailVerified;
    private string $password;
    private string $profilepictureUrl;

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

    public function isEmailVerified() {
        return $this->emailVerified;
    }

    public function setId(){
        $this->id = "placeholder";
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
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

    public static function getById($id) {
        // code to retrieve the user from the database based on their id, including emailVerified and profilePictureUrl
    }

    public static function getByEmail($email) {
        // code to retrieve the user from the database based on their email, including emailVerified and profilePictureUrl
    }



}