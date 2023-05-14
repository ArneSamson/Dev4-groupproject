<?php
include_once("bootstrap.php");

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
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include_once("navbar.php"); ?>

    <div class="containerHome">
        <div class="prompt-cards">
            <?php if (empty($prompts)) : ?>
                <p>No prompts found.</p>
            <?php else : ?>
                <?php foreach ($prompts as $prompt) : ?>
                    <div class="prompt-card">
                        <?php
                            $fileExtension = pathinfo($prompt['pictures'], PATHINFO_EXTENSION);
                            $imagePath = "../media/" . basename($prompt['pictures'], ".tmp") . "." . $fileExtension;
                        ?>
                        <div class="prompt-card__image">
                            <div class="prompt-card__model"><?php echo $prompt['model']; ?></div>
                            <img src="<?php echo $imagePath; ?>" alt="Prompt Image">
                        </div>
                        <div class="prompt-card__details">
                            <div class="prompt-card__name"><?php echo $prompt['name']; ?></div>
                            <div class="prompt-card__price"><?php echo $prompt['price']; ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>