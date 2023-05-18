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

// Set the number of prompts to show on each page
$limit = 5;

// Get the total number of prompts
$statement = $conn->prepare("SELECT COUNT(*) FROM prompts WHERE online = 1 AND user_id = :user_id");
$statement->bindValue(':user_id', $user_id);
$statement->execute();
$total = $statement->fetchColumn();

// Calculate the number of pages
$pages = ceil($total / $limit);

// Get the current page number
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Calculate the offset
$offset = ($page - 1) * $limit;

$statement = $conn->prepare("SELECT * FROM prompts WHERE online = 1 ORDER BY date DESC LIMIT :limit OFFSET :offset");
$statement->bindParam(':limit', $limit, PDO::PARAM_INT);
$statement->bindParam(':offset', $offset, PDO::PARAM_INT);
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
    <body style="height: auto">

    <?php include_once("navbar.php"); ?>

    <div style="margin-bottom: 100px;">
        <h1 style="padding-top: 100px;">My prompts</h1>
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
                <h2><a href="promptDetails.php?id=<?php echo $prompt['id']; ?>"><?php echo $prompt['name']; ?></a></h2>
                <p> <?php echo $days_ago ?> </p>
                <img src=<?php echo $prompt['pictures']?> style="width:300px">
                <p>Description: <?php echo $prompt['description'] ?> </p>
                <p>tags: <?php echo $prompt['categories'] ?> </p>
                <p>Price: <?php echo $prompt['price'] ?> tokens</p>
            </div>
            
        <?php endforeach; ?>
        
        <div style="padding-top: 50px;">
        <?php if ($page > 1) : ?>
            <a href="?page=<?php echo $page - 1; ?>" style="padding-right: 50px;">Previous Page</a>
        <?php endif; ?>
        
        <?php if ($page < $pages) : ?>
            <a href="?page=<?php echo $page + 1; ?>" style="padding-right: 50px;">Next Page</a>
        <?php endif; ?>
        </div>

        
        
    </div>
        

    </body>
</html>