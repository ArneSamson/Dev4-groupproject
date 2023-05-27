<?php
    include_once("bootstrap.php");
    include_once("../inc/functions.inc.php");

    // Make sure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    // Retrieve the prompt and user details from the URL
    $promptData = getPromptFromURL($_GET['id']);
    $prompt = $promptData['prompt'];
    $user = $promptData['user'];
    $isCurrentUserPrompt = $promptData['isCurrentUserPrompt'];



    $comments = Comment::getCommentsfromPrompt($prompt['id']);

    if(isset($_POST['comment']) && !empty($_POST['comment'])){

        $commentContent = htmlspecialchars($_POST['comment']);

        $comment = new Comment();
        $comment->setUserId($_SESSION['user_id']);
        $comment->setPromptId($_POST['prompt_id']);
        $comment->setContent($commentContent);
        $comment->setTimeStamp(date("Y-m-d H:i:s"));
        $comment->setIsDeleted(0);
        $comment->saveComment();

        // Redirect to prevent form resubmission
        header("Location: promptDetails.php?id=" . $_GET['id']);
        exit();
    }

    

?>

<!DOCTYPE html>
<html>
<head>
    <title>Prompt details</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include_once("navbar.php"); ?>
    <div class="containerPromptDetails">
        <div class="prompt-details__info">
            <h2><?php echo $prompt['name']; ?></h2>
            <!-- link to user profile -->
            <p>By: <a href="profile.php?user_id=<?php echo $user['id']; ?>"><?php echo $user['username']; ?></a></p>
            <p>Words: <?php echo wordCount($prompt['prompt']); ?></p>
            <p>Description: <br><?php echo $prompt['description']; ?></p>
            <p class="price">Price: <?php echo "€" . $prompt['price']; ?></p>
            <a href="#" class="get-prompt-button">Get Prompt</a>
        </div>
        <div class="prompt-details__image">
            <?php
            $fileExtension = pathinfo($prompt['pictures'], PATHINFO_EXTENSION);
            $imagePath = "../media/" . basename($prompt['pictures'], ".tmp") . "." . $fileExtension;
            ?>
            <img src="<?php echo $imagePath; ?>" alt="Prompt Image">
        </div>
        
        <div>
            <?php if ($isCurrentUserPrompt === true) : ?>
                <div class="prompt-details__actions">
                    <a href="editPrompt.php?id=<?php echo $prompt['id']; ?>" class="edit-prompt-button">Edit Prompt</a>
                    <a href="deletePrompt.php?id=<?php echo $prompt['id']; ?>" class="delete-prompt-button">Delete Prompt</a>
                </div>
            <?php endif; ?>
            
    
            <div class="prompt-details__comment-form">
                <h3>Add a Comment</h3>
                <form method="POST">
                    <input type="hidden" name="prompt_id" value="<?php echo $prompt['id']; ?>">
                    <textarea name="comment" placeholder="Enter your comment" style="resize: none;"></textarea>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>

        <div class="prompt-details__comments" style="margin-left: 20px; margin-right: 20px">
            <h3>Comments</h3>
            <?php foreach ($comments as $comment) : ?>

                <?php
                $commenter = User::getById($comment['user_id']);
                $commenterName = $commenter['username'];
                ?>
                <div class="comment" style="background-color:azure; padding:10px; margin-top: 20px">
                    <p><?php echo $comment['content']; ?></p>
                    <p>By: <?php echo $commenterName?></p>
                    <p>Posted at: <?php echo $comment['timestamp']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        
        <script>
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        </script>


    </div>
</body>
</html>