<?php
include_once("bootstrap.php");

if (!isset($_SESSION["user_id"])) {
    $user_id = "";
} else {
    $user_id = $_SESSION["user_id"];
    $user_role = $_SESSION["role"];
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: php/login.php");
    exit;
}

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Get the filtered prompts based on the search query
$prompts = Prompts::getPromptsBySearchQuery($searchQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>AI Prompt Home</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
</head>
<body>
    <?php include_once("navbar.php"); ?>
    <div class="containerPrompts">
        <div class="filter">
        <form method="GET" action="prompts.php">
            <button class="filter__clear">Clear filter</button>
            <div class="filter__section">
                <div class="filter__section-title">Sort by</div>
                <label><input type="checkbox" name="sort"> Name</label>
                <label><input type="checkbox" name="sort"> Price (up)</label>
                <label><input type="checkbox" name="sort"> Price (down)</label>
            </div>
            <div class="filter__section">
                <div class="filter__section-title">Model</div>
                <label><input type="checkbox" name="model[]" value="all"> All</label>
                <label><input type="checkbox" name="model[]" value="dalle"> Dalle</label>
                <label><input type="checkbox" name="model[]" value="midjourney"> Midjourney</label>
                <label><input type="checkbox" name="model[]" value="stable_diffusion"> Stable Diffusion</label>
                <label><input type="checkbox" name="model[]" value="lexica"> Lexica</label>
            </div>
            <div class="filter__section">
                <div class="filter__section-title">Category</div>
                <label><input type="checkbox" name="category[]" value="all"> All</label>
                <label><input type="checkbox" name="category[]" value="space"> Space</label>
                <label><input type="checkbox" name="category[]" value="game"> Game</label>
                <label><input type="checkbox" name="category[]" value="car"> Car</label>
                <label><input type="checkbox" name="category[]" value="nature"> Nature</label>
                <label><input type="checkbox" name="category[]" value="portrait"> Portrait</label>
            </div>
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="filter__apply">Apply Changes</button>
        </form>
    </div>

        <div class="prompt-cards">
            <?php if (empty($prompts)) : ?>
                <p class="prompt-cards-not-found">No prompts found.</p>
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
                    <div class="prompt-card__price"><?php echo "€ " . $prompt['price']; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>

    </div>
</body>


</html>