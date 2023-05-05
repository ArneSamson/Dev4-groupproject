<?php

error_reporting(E_ALL);

include_once("bootstrap.php");

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if user is not logged in or user ID is empty
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    var_dump("User is not logged in or user id is empty");
}




?>


<!DOCTYPE html>
<html>
<head>
	<title>AI Prompt Home</title>
	<link rel="stylesheet" href="../css/style.css">
</head>
<body>
	<nav class="navbar">
		<div class="navbar__logo">Prompt Engine</div>
		<div class="navbar__buttons">
			<div class="navbar__button navbar__button--credit">Credits: 0</div>
			<a href="<?php echo $profile_url; ?>">Edit Profile</a>
			<a href="#" class="navbar__button navbar__button--logout">Log out</a>
		</div>
	</nav>

	<div class="containerHome">
		<h1>Welcome to Prompt Engine!</h1>
		<button class="btn btn--upload">Upload Prompt</button>
	</div>
</body>
</html>
