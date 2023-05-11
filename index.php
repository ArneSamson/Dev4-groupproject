<?php

include_once("php/bootstrap.php");

if (!isset($_SESSION["user_id"])) {
    $user_id = "";
} else {
    $user_id = $_SESSION["user_id"];
    $user_role = $_SESSION["role"];
    var_dump($_SESSION);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>AI Prompt Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar__logo">Prompt Engine</div>
        <div class="navbar__buttons">
            <div class="navbar__button navbar__button--credit">Credits: 0</div>
            <a href="php/profile.php?user_id=<?php echo $user_id; ?>">Edit Profile</a>
            <?php if ($user_role === "admin") : ?>
                <a href="php/roles.php">Roles</a>
            <?php endif; ?>
            <a href="php/logout.php" class="navbar__button navbar__button--logout">Log out</a>
        </div>
    </nav>

    <div class="containerHome">
        <h1>Welcome to Prompt Engine!</h1>
        <button onclick="window.location.href='php/upload.php'" class="btn btn--upload">Upload Prompt</button>
    </div>
</body>
</html>