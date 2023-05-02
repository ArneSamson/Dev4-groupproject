<?php

require_once(__DIR__ . '/../vendor/autoload.php');
use SendGrid\Mail\Mail;

class EmailVerification {
    private $apiKey;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function sendVerificationEmail($username, $emailAddress, $verificationCode) {
        $email = new Mail();
        $email->setFrom("ladiske@hotmail.com", "Ladis");
        $email->setSubject("Verify your email address");
        $email->addTo($emailAddress, $username);
        
        $email->addContent(
            "text/plain", 
            "Thank you for signing up! Please click on the following link to verify your email address: http://localhost/prompts/Dev4-groupproject/php/verification.php?verification_code=$verificationCode"
        );

        $sendgrid = new SendGrid($this->apiKey);
        try {
            $response = $sendgrid->send($email);
        } catch (Throwable $e) {
            throw new Exception("Error sending verification email: " . $e->getMessage());
        }
    }

   
}

?>