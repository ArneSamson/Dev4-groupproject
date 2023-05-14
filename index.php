<?php
include_once("php/bootstrap.php");

if (!isset($_SESSION["user_id"])) {
    $user_id = "";
} else {
    $user_id = $_SESSION["user_id"];
    $user_role = $_SESSION["role"];
    var_dump($_SESSION);
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: php/login.php");
    exit;
}

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$prompts = Prompts::getPromptsBySearchQuery($searchQuery);

?>

<!DOCTYPE html>
<html>
<head>
    <title>AI Prompt Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include_once("php/navbar.php"); ?>


    <div class="containerHome">
        <h1>DALL·E, GPT, Midjourney, Stable Diffusion, ChatGPT Prompt Marketplace</h1>
        <button onclick="window.location.href='php/upload.php'" class="btn btn--upload">Upload Prompt</button>
        <button onclick="window.location.href='php/prompts.php'" class="btn btn--upload">View Prompts</button>
    </div>
</body>
</html>