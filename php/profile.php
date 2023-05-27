<?php
require_once 'bootstrap.php';
include_once("../inc/functions.inc.php");

if (!isset($_SESSION["user_id"])) {
    $user_id = "";
} else {
    $user_id = $_SESSION["user_id"];
    $user_role = $_SESSION["role"];
}

//function to make display prompts according to page number
$promptData = Prompts::promptPageWithID($_GET['user_id']);
$data = $promptData['data'];
$page = $promptData['page'];
$pages = $promptData['pages'];

$ownAccount = false;
if ($_GET['user_id'] == $user_id) {
    $ownAccount = true;
}

$user = User::getByID($_GET['user_id']);
$user_name = $user['username'];
$user_email = $user['email'];
$user_role = $user['role'];
$user_picture = $user['imagepath'];
$user_credits = $user['credits'];


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
    <link rel="icon" href="../media/favicon/icon.png" type="image/x-icon"/>


</head>
    <body style="height: auto">

    <?php include_once("navbar.php"); ?>

    <?php if($ownAccount === true) : ?>
        <div>
            <h1 style="padding-top: 100px;">My account</h1>
            <img src="<?php echo $user_picture ?>" style="width:100px">
            <h3><?php echo $user_name ?></h3>
            <p>Email: <?php echo $user_email ?></p>
            <p>Role: <?php echo $user_role ?></p>
            <p>Credits: <?php echo $user_credits ?></p>
        </div>
    <?php else : ?>
        <div>
            <h1 style="padding-top: 100px;">Account</h1>
            <img src="<?php echo $user_picture ?>" style="width:100px">
            <p><?php echo $user_name ?></p>
        </div>
    <?php endif; ?>


    <div style="margin-bottom: 100px;">

        <?php if($ownAccount === true) : ?>
            <h1 style="padding-top: 100px;">My prompts</h1>
        <?php else : ?>
            <h1 style="padding-top: 100px;"><?php echo $user_name?>'s Prompts</h1>
        <?php endif; ?>
        
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