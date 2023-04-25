<?php

class User {
    private $id;
    private string $username;
    private string $password;
    private string $profilepicture;
    private bool $verified;

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
            $this->profilepicture = $p_sprofilepicture;
            }
    }
    public function setPassword($p_spassword){
        if(empty($p_spassword))
            throw new Exception("Password cannot be empty");
        else{
            $this->password = $p_spassword;
            }
    }
    public function setVerification($p_bverfied){

    }
    public function setId(){
        $this->id = "placeholder";
    }
























}