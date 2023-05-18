<?php
include_once("bootstrap.php");
include_once("../inc/functions.inc.php");

// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the prompt and user details from the URL
$promptData = getPromptFromURL($_GET['id']);
$prompt = $promptData['prompt'];
$user = $promptData['user'];
$isCurrentUserPrompt = $promptData['isCurrentUserPrompt'];

// Check if the user is the owner of the prompt
if ($isCurrentUserPrompt === true) {
    // Handle prompt deletion if the user confirms
    if (isset($_POST['confirm_delete'])) {
        Prompts::invalidatePrompt($prompt['id']);
        // Redirect to a confirmation page or another appropriate location
        header("Location: prompts.php");
        exit();
    }
} else {
    // Redirect if the user is not the owner of the prompt
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Prompt</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include_once("navbar.php"); ?>

    <div class="container">
        <h2>Delete Prompt</h2>
        <p>Are you sure you want to delete this prompt?</p>
        <h3><?php echo $prompt['name']; ?></h3>
        <img src="<?php echo $prompt['pictures']; ?>" alt="Prompt Image" style="width:500px">
        <p>Description: <?php echo $prompt['description']; ?></p>

        <form method="POST" action="">
            <input type="submit" name="confirm_delete" value="Delete">
        </form>
    </div>
</body>
</html>
