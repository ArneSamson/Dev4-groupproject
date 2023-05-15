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
}
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
            <p>User: <?php echo $user['username']; ?></p>
            <p>Words: <?php echo wordCount($prompt['description']); ?></p>
            <p>Description: <br><?php echo $prompt['prompt']; ?></p>
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
    </div>
</body>
</html>