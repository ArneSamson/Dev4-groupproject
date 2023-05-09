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
    private $role;


    public static function getAllUsers() {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
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
    
    public function register() {
        $verificationCode = bin2hex(random_bytes(32));
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
    
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO users (username, email, password, email_verification, role) VALUES (:username, :email, :password, :emailVerification, :role)");
        $statement->bindValue(":username", $this->username);
        $statement->bindValue(":email", $this->email);
        $statement->bindValue(":password", $hashedPassword);
        $statement->bindValue(":emailVerification", $verificationCode);
        $statement->bindValue(":role", "user"); // Assign the default role "user"
        $result = $statement->execute();
        $this->verificationCode = $verificationCode; // set the verification code
    
        return $result;
    }

    public static function verifyUser($verificationCode) {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE email_verification = :verificationCode");
        $statement->bindValue(":verificationCode", $verificationCode);
        $statement->execute();
        $user = $statement->fetch();

        if ($user) {
            $statement = $conn->prepare("UPDATE users SET verified = 1 WHERE id = :userId");
            $statement->bindValue(":userId", $user['id']);
            $statement->execute();
            return true;
        } else {
            return false;
        }
    }
    
    public function getVerificationCode() {
        return $this->verificationCode;
    }

    public static function getByUsername($username) {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function getByEmail($email) {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function setRole($role) {
        $this->role = $role;
    }
    
    public function getRole() {
        return $this->role;
    }
    
    public function isAdmin() {
        return $this->role === 'admin';
    }
    
    public function isModerator() {
        return $this->role === 'moderator';
    }
    
    public function grantAdminRole() {
        if ($this->isAdmin()) {
            throw new Exception("User already has admin role");
        }
        
        $this->setRole('admin');
    }
    
    public function grantModeratorRole() {
        if ($this->isModerator()) {
            throw new Exception("User already has moderator role");
        }
        
        $this->setRole('moderator');
    }
    
    public function revokeAdminRole() {
        if (!$this->isAdmin()) {
            throw new Exception("User does not have admin role");
        }
        
        $this->setRole('user');
    }
    
    public function revokeModeratorRole() {
        if (!$this->isModerator()) {
            throw new Exception("User does not have moderator role");
        }
        
        $this->setRole('user');
    }

    

    public function authenticate($password) {
        return password_verify($password, $this->password);
    }

    public static function login($usernameOrEmail, $password) {
        $user = self::getByUsername($usernameOrEmail);

        if (!$user) {
            $user = self::getByEmail($usernameOrEmail);
        }

        if (!$user) {
            return false;
        }

        if (!$user['emailVerified']) {
            return false;
        }

        if (!$user->authenticate($password)) {
            return false;
        }

        $_SESSION['userId'] = $user['id'];
        return true;
    }

    public static function logout() {
        session_destroy();
    }

    public static function isLoggedIn() {
        return isset($_SESSION['userId']);
    }
}