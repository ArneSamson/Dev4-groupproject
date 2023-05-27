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

?>

<!DOCTYPE html>
<html>
<head>
    <title>Prompt details</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include_once("navbar.php"); ?>
    <div class="containerPromptDetails">
        <div class="prompt-details__info">
            <h2><?php echo $prompt['name']; ?></h2>
            <!-- link to user profile -->
            <p>By: <a href="profile.php?user_id=<?php echo $user['id']; ?>"><?php echo $user['username']; ?></a></p>
            <p>Words: <?php echo wordCount($prompt['prompt']); ?></p>
            <p>Description: <br><?php echo $prompt['description']; ?></p>
            <p class="price">Price: <?php echo "€" . $prompt['price']; ?></p>
            <a href="#" class="get-prompt-button">Get Prompt</a>
        </div>
        <div class="prompt-details__image">
            <?php
            $fileExtension = pathinfo($prompt['pictures'], PATHINFO_EXTENSION);
            $imagePath = "../media/" . basename($prompt['pictures'], ".tmp") . "." . $fileExtension;
            ?>
            <img src="<?php echo $imagePath; ?>" alt="Prompt Image">
        </div>
        
        <?php if ($isCurrentUserPrompt === true) : ?>
            <div class="prompt-details__actions">
                <a href="editPrompt.php?id=<?php echo $prompt['id']; ?>" class="edit-prompt-button">Edit Prompt</a>
                <a href="deletePrompt.php?id=<?php echo $prompt['id']; ?>" class="delete-prompt-button">Delete Prompt</a>
            </div>
        <?php endif; ?>
        
    </div>
</body>
</html>