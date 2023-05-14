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

// Retrieve prompts from the database based on the search query
try {
    $conn = Db::getInstance();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $prompts = array();

    if (!empty($searchQuery)) {
        $query = $conn->prepare("SELECT * FROM prompts WHERE onlin = 1 AND name LIKE :search");
        $query->bindValue(":search", "%$searchQuery%");
        $query->execute();
        $prompts = $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $query = $conn->prepare("SELECT * FROM prompts WHERE onlin = 1");
        $query->execute();
        $prompts = $query->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $message = "Try again later: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>AI Prompt Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar__logo">Prompt Engine</div>
        <form method="GET" action="">
        <div class="navbar__search">
            <form action="index.php" method="GET">
                <input type="text" placeholder="Search" name="search" class="navbar__search-input" value="<?php echo $searchQuery; ?>">
                <button type="submit" class="navbar__search-button">Search</button>
            </form>
        </div>
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
        <h1>DALL·E, GPT, Midjourney, Stable Diffusion, ChatGPT
Prompt Marketplace</h1>
        <button onclick="window.location.href='php/upload.php'" class="btn btn--upload">Upload Prompt</button>
        <div class="prompt-cards">
            <?php if (empty($prompts)) : ?>
                <p>No prompts found.</p>
            <?php else : ?>
                <?php foreach ($prompts as $prompt) : ?>
                    <?php
                        $fileExtension = pathinfo($prompt['pictures'], PATHINFO_EXTENSION);
                        $imagePath = "media/" . basename($prompt['pictures'], ".tmp") . "." . $fileExtension;
                    ?>
                    <img src="<?php echo $imagePath; ?>" alt="Prompt Image">
                    <h3><?php echo $prompt['name']; ?></h3>
                    <p><?php echo $prompt['description']; ?></p>
                    <p><?php echo $prompt['price']; ?></p>
                    <p><?php echo $prompt['tags']; ?></p>
                    <p><?php echo $prompt['model']; ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>