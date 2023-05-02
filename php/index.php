<?php

include_once("bootstrap.php");

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $profile_url = "../php/profile.php?user_id=" . $user_id;
} else {
    $profile_url = "../php/profile.php?user_id=";
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
			<a href="<?php echo $$profile_url; ?>">Edit Profile</a>
			<a href="#" class="navbar__button navbar__button--logout">Log out</a>
		</div>
	</nav>

	<div class="containerHome">
		<h1>Welcome to Prompt Engine!</h1>
		<button class="btn btn--upload">Upload Prompt</button>
	</div>
</body>
</html>
