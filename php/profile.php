<?php
require_once 'bootstrap.php';
include_once("../inc/functions.inc.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

//function to make display prompts according to page number
$promptData = Prompts::promptPageWithID($_GET['user_id']);
$data = $promptData['data'];
$page = $promptData['page'];
$pages = $promptData['pages'];

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