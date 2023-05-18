<?php
include_once("bootstrap.php");
include_once("../inc/functions.inc.php");

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the prompt ID from the URL

    // Retrieve the prompt and user details from the URL
    $promptData = getPromptFromURL($_GET['id']);
    $prompt = $promptData['prompt'];
    $user = $promptData['user'];
    $isCurrentUserPrompt = $promptData['isCurrentUserPrompt'];



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Prompt</title>
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

    <!-- if session-user_id === user_id from prompt -->
    <?php if ($isCurrentUserPrompt === true) : ?>
        <?php include_once("navbar.php"); ?>
    
    <?php else : ?>
        <?php header("Location: ../index.php"); ?>

    <?php endif; ?>

    
</body>
</html>