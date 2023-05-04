<?php

include_once("bootstrap.php");

if(!isset($_SESSION["user_id"])) {
	$user_id = "";
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
			<a href="profile.php?user_id=<?php echo $user_id; ?>">Edit Profile</a>
			<a href="logout.php" class="navbar__button navbar__button--logout">Log out</a>
		</div>
	</nav>

	<div class="containerHome">
		<h1>Welcome to Prompt Engine!</h1>
		<button onclick="window.location.href='upload.php'" class="btn btn--upload">Upload Prompt</button>
	</div>
</body>
</html>
