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
    <nav class="navbar">
        <a href="index.php" class="navbar__logo">Prompt Engine</a>
        <form action="php/prompts.php" method="GET" class="navbar__search">
            <input type="text" placeholder="Search" name="search" class="navbar__search-input" value="<?php echo $searchQuery; ?>">
            <button type="submit" class="navbar__search-button">Search</button>
        </form>
        <div class="navbar__buttons">
            <div class="navbar__button--credit">Credits: 0</div>
            <a href="php/profile.php?user_id=<?php echo $user_id; ?>" class="navbar__button">Edit Profile</a>
            <?php if ($user_role === "admin") : ?>
                <a href="php/roles.php" class="navbar__button">Roles</a>
            <?php endif; ?>
            <?php if ($user_role === "admin" || $user_role === "moderator") : ?>
                <a href="php/validate.php" class="navbar__button">Validate</a>
            <?php endif; ?>
            <a href="?logout=true" class="navbar__button navbar__button--logout">Log out</a>
        </div>
    </nav>

    <div class="containerHome">
        <h1>DALL·E, GPT, Midjourney, Stable Diffusion, ChatGPT Prompt Marketplace</h1>
        <button onclick="window.location.href='php/upload.php'" class="btn btn--upload">Upload Prompt</button>
        <button onclick="window.location.href='php/prompts.php'" class="btn btn--upload">View Prompts</button>
    </div>
</body>
</html>