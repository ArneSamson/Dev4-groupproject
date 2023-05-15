<?php
require_once 'bootstrap.php';
include_once("../inc/functions.inc.php");

if (!isset($_SESSION["user_id"])) {
    $user_id = "";
} else {
    $user_id = $_SESSION["user_id"];
    $user_role = $_SESSION["role"];
}

$user_id = $_SESSION['user_id'];
$conn = Db::getInstance();
$statement = $conn->prepare("SELECT * FROM prompts WHERE online = 1 ORDER BY date DESC");
$statement->execute();
$data = $statement->fetchAll(PDO::FETCH_ASSOC);

?>


<!-- Output the data in an HTML table -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace</title>
    <link rel="stylesheet" href="../css/style.css">

</head>
    <body>

    <nav class="navbar">
        <div class="navbar__logo">Prompt Engine</div>
        <div class="navbar__buttons">
            <div class="navbar__button navbar__button--credit">Credits: 0</div>
            <a href="php/profile.php?user_id=<?php echo $user_id; ?>">Edit Profile</a>
            <?php if ($user_role === "admin") : ?>
                <a href="php/roles.php">Roles</a>
            <?php endif; ?>
            <a href="php/logout.php" class="navbar__button navbar__button--logout">Log out</a>
        </div>
    </nav>

    <div style="margin-top: 2500px;">
        <h1 style="padding-top: 50px;">Marketplace</h1>
        <?php foreach ($data as $prompt) : ?>
    
            <?php
            $date_diff = time() - strtotime($prompt['date']);
            $days_ago = round($date_diff / (60 * 60 * 24));
            if ($days_ago < 1) {
                $days_ago = "today";
            } else {
                $days_ago = $days_ago . " days ago";
            }
            ?>
    
            <div style="padding-top: 50px;">
                <h2> <?php echo $prompt['name'] ?> </h2>
                <p> <?php echo $days_ago ?> </p>
                <img src=<?php echo $prompt['pictures']?>>
                <p>Description: <?php echo $prompt['description'] ?> </p>
                <p>tags: <?php echo $prompt['categories'] ?> </p>
                <p>Price: <?php echo $prompt['price'] ?> tokens</p>
            </div>
            
        <?php endforeach; ?>
    
    </div>

        

    </body>
</html>