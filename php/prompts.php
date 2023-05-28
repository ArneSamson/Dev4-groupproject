<?php
include_once("bootstrap.php");

$selectedModels = isset($_GET['model']) ? $_GET['model'] : array();
$selectedCategories = isset($_GET['category']) ? $_GET['category'] : '';

$sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : '';

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Add $sortBy to the query string when generating the prompts list
    $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
    $prompts = Prompts::getFilteredPrompts($searchQuery, $selectedModels, $selectedCategories, $sortBy);
}

//function to uncheck all checkboxes and radio buttons

function clearFilters() {
    // Redirect to the same page to clear the filters
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

// Check if the clear button is clicked
if (isset($_GET['clear'])) {
    clearFilters();
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $promptId = isset($_POST['promptId']) ? $_POST['promptId'] : null;
    $likes = isset($_POST['likes']) ? $_POST['likes'] : 0;

    //if likes is < 0 set it to 0
    if ($likes < 0) {
        $likes = 0;
    }
  
    if ($promptId !== null) {
        // Update the likes in the database
        $update = Prompts::updateLikes($promptId, $likes);
      
        if ($update) {
            // Send the response back to the client
            $response = array("success" => true, "likes" => $likes);
            echo json_encode($response);
            exit;
        } else {
            // Failed to update the likes in the database
            $response = array("success" => false);
            echo json_encode($response);
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AI Prompt Home</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../media/favicon/icon.png" type="image/x-icon"/>
    <script src="../ajax/like.js"></script>
</head>
<body>
    <?php include_once("navbar.php"); ?>
    <div class="containerPrompts">
        <div class="filter">
        <form method="GET" action="prompts.php">
            <button type="submit" class="filter__clear" name="clear">Clear Filters</button>
            <div class="filter__section">
            <div class="filter__section-title">Sort by</div>
                <label><input type="radio" name="sortBy" value="name"> Name</label>
                <label><input type="radio" name="sortBy" value="price_up"> Price (up)</label>
                <label><input type="radio" name="sortBy" value="price_down"> Price (down)</label>
            </div>
            <div class="filter__section">
            <div class="filter__section-title">Model</div>
            <label><input type="checkbox" name="model[]" value="all" <?php echo in_array("all", $selectedModels) ? "checked" : ""; ?>> All</label>
            <label><input type="checkbox" name="model[]" value="dalle" <?php echo in_array("dalle", $selectedModels) ? "checked" : ""; ?>> Dalle</label>
            <label><input type="checkbox" name="model[]" value="midjourney" <?php echo in_array("midjourney", $selectedModels) ? "checked" : ""; ?>>
            <label><input type="checkbox" name="model[]" value="stablediffusion" <?php echo in_array("stable_diffusion", $selectedModels) ? "checked" : ""; ?>> Stable Diffusion</label>
            <label><input type="checkbox" name="model[]" value="lexica" <?php echo in_array("lexica", $selectedModels) ? "checked" : ""; ?>> Lexica</label>
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
            <button type="submit" class="btn--upload">Apply Changes</button>
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
                    <a href="promptDetails.php?id=<?php echo $prompt['id']; ?>">
                        <div class="prompt-card__image">
                            <div class="prompt-card__model"><?php echo $prompt['model']; ?></div>
                            <img src="<?php echo $imagePath; ?>" alt="Prompt Image">
                        </div>
                        <div class="prompt-card__details">
                            <div class="prompt-card__name"><?php echo $prompt['name']; ?></div>
                            <div class="prompt-card__price"><?php echo "€ " . $prompt['price']; ?></div>
                        </div>
                    </a>
                    <button class="likeButton" data-prompt-id="<?php echo $prompt['id']; ?>" data-likes="<?php echo $prompt['likes']; ?>" onclick="updatePromptLikes(this)">
                        Like
                    </button>
                    <p class="likeCount"><?php echo $prompt['likes']." likes"; ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>