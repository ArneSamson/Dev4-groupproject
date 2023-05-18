<?php
include_once("bootstrap.php");

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the prompt ID from the URL
if (isset($_GET['id'])) {
    $promptId = $_GET['id'];

    // Get the prompt details based on the prompt ID
    $prompt = Prompts::getPromptById($promptId);

    $user = User::getById($prompt['user_id']);


    // Check if the user exists
    if ($user === null) {
        header("Location: prompts.php");
        exit();
    }

    //function to get the word count of a description of the prompt
    function wordCount($string) {
        $string = strip_tags($string);
        $string = preg_replace('/\s+/', ' ', $string);
        $words = explode(" ", $string);
        return count($words);
    }

    // Check if the prompt belongs to the current user
    $isCurrentUserPrompt = false;
    if ($_SESSION['user_id'] === $prompt['user_id']) {
        $isCurrentUserPrompt = true;
    }

}
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