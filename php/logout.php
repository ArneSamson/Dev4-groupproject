<?php
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Clear the session cookie
setcookie(session_name(), '', time() - 3600, '/');

// Redirect to the login page
header("Location: login.php");
exit;
