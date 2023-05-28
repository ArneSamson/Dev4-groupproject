<?php
    include_once("php/bootstrap.php");

    //function to make display prompts according to page number
    $promptData = Prompts::promptPage();
    $data = $promptData['data'];
    $page = $promptData['page'];
    $pages = $promptData['pages'];

    // Iterate over the prompts data and remove "..\" from the image path
    foreach ($data as &$prompt) {
        $prompt['pictures'] = str_replace(['..\\', '..//', '../'], '', $prompt['pictures']);
        $prompt['pictures'] = str_replace(['media/', 'media\\'], '', $prompt['pictures']);
    }
    unset($prompt); // Unset the reference variable after the loop

?>  

<!DOCTYPE html>
<html>
<head>
    <title>AI Prompt Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="height:auto">
    <?php include_once("php/navbar.php"); ?>


    <div class="containerHome">
        <h1>DALL·E, GPT, Midjourney, Stable Diffusion, ChatGPT Prompt Marketplace</h1>
        <button onclick="window.location.href='php/upload.php'" class="btn btn--upload">Upload Prompt</button>
        <button onclick="window.location.href='php/prompts.php'" class="btn btn--upload">View Prompts</button>
    </div>
    <h1 style="padding-top: 100px;">Latest Prompts</h1>

    <div style="margin-bottom: 100px; display:flex; flex-wrap: wrap; gap: 20px; justify-content: center; align-items: center;">


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
                <h2><a href="php/promptDetails.php?id=<?php echo $prompt['id']; ?>"><?php echo $prompt['name']; ?></a></h2>
                <p> <?php echo $days_ago ?> </p>
                <?php
                $imagePath = "media/" . $prompt['pictures'];
                ?>
                <img src="<?php echo $imagePath?>" style="width: 300px;">
                <p>Description: <?php echo $prompt['description'] ?> </p>
                <p>tags: <?php echo $prompt['categories'] ?> </p>
                <p>Price: <?php echo $prompt['price'] ?> tokens</p>
            </div>
            
        <?php endforeach; ?>
        
        <div style="padding-top: 50px;">
            <?php if ($page > 1) : ?>
                <a href="?page=<?php echo $page - 1; ?>" class="btn--page">Previous Page</a>
            <?php endif; ?>
            
            <?php if ($page < $pages) : ?>
                <a href="?page=<?php echo $page + 1; ?>" class="btn--page">Next Page</a>
            <?php endif; ?>
        </div>

        
        
    </div>

</body>
</html>