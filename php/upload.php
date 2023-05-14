<?php

include_once("bootstrap.php");

// Check if error message is set
$error = isset($_GET["error"]) ? $_GET["error"] : null;

// Redirect to login page if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION["user_id"];
var_dump($user_id);

try {
    $conn = Db::getInstance();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the database connection is successful
    if ($conn) {
      echo "Database connection successful!";
  } else {
      echo "Failed to connect to the database.";
  }
} catch (PDOException $e) {
    $message = "Try again later: " . $e->getMessage();
    exit($message);
}

$prompts = new Prompts($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Form submitted!";
    //var_dump($_POST);
    //var_dump($_FILES);

    $prompts->setName($_POST["title"]);
    $prompts->setDescription($_POST["description"]);
    $prompts->setPrompt($_POST["prompt"]);
    $prompts->setPrice($_POST["price"]);
    $prompts->setModel($_POST["model-type"]);
    $prompts->setTags($_POST["tags"]);
    $prompts->setSelectedCategories($_POST["categories"]);

    // Handle uploaded file
    $prompts->setFileName($_FILES["image-upload"]["name"]);
    $prompts->setFileTempName($_FILES["image-upload"]["tmp_name"]);
    $prompts->setFileSize($_FILES["image-upload"]["size"]);
    $prompts->setFileError($_FILES["image-upload"]["error"]);

    if ($prompts->getFileError() !== UPLOAD_ERR_OK) {
        $message = "Upload failed with error code " . $prompts->getFileError() . ".";
        exit;
    }

    // Check file size
    if ($prompts->getFileSize() > 1000000) {
        $message = "File is too big";
        exit;
    }

    // Check file name for invalid characters
    if (!preg_match('/^[a-zA-Z0-9_]+\.[a-zA-Z0-9]{3,4}$/', $prompts->getFileName())) {
        $message = "File name is not correct";
        exit;
    }

    $prompts->handleUpload();
    header("Location: ../index.php");
    exit;
}
?>

    <!DOCTYPE html>
    <html lang="en">
    
    <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Upload your prompt</title>
      <link rel="stylesheet" href="../css/style.css" />
    </head>
    
    <body>
      <!-- <?php include_once("../inc/functions.inc.php"); ?>  -->
      <!-- This is the nav bar -->
    
      <div class="form-container">
<form class="uploadform" enctype="multipart/form-data" method="POST">
  <h2>Upload a new prompt</h2>
  <?php if (isset($error)) : ?> <!-- if error message is set -->
    <p class="errormessage"><?php echo $error ?></p> <!-- display error message -->
  <?php endif; ?>

  <fieldset>
    <legend>Basic Information</legend>

    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required>

    <label for="description">Description:</label>
    <textarea style="resize: none;" id="description" name="description" required></textarea>

    <label for="prompt">Prompt:</label>
    <textarea style="resize: none;" id="prompt" name="prompt" required></textarea>

    <label for="price">Price:</label>
    <select id="price" name="price">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
    </select>

    <label for="model-type">Model:</label>
    <select id="model-type" name="model-type">
      <option value="dalle">DALL-E</option>
      <option value="midjourney">Midjourney</option>
      <option value="lexica">Lexica</option>
      <option value="stablediffusion">Stable Diffusion</option>
    </select>
  </fieldset>

  <fieldset>
    <legend>Image and Tags</legend>

    <label for="image-upload">Image:</label>
    <input type="file" id="image-upload" name="image-upload" accept="image/*" required>

    <label for="tags">Tags:</label>
    <input type="text" id="tags" name="tags" required>
  </fieldset>

  <fieldset>
    <legend>Category</legend>

    <input type="checkbox" id="animals" name="categories[]" value="Animals">
    <label for="animals" class="checkbox-label">Animals</label><br>

    <input type="checkbox" id="3D" name="categories[]" value="3D">
    <label for="3D" class="checkbox-label">3D</label><br>

    <!-- Add more checkboxes here -->

        <!-- Example additional checkboxes -->
        <input type="checkbox" id="space" name="categories[]" value="Space">
        <label for="space" class="checkbox-label">Space</label><br>

        <input type="checkbox" id="game" name="categories[]" value="Game">
        <label for="game" class="checkbox-label">Game</label><br>

        <input type="checkbox" id="car" name="categories[]" value="Car">
        <label for="car" class="checkbox-label">Car</label><br>

        <input type="checkbox" id="nature" name="categories[]" value="Nature">
        <label for="nature" class="checkbox-label">Nature</label><br>

        <input type="checkbox" id="portrait" name="categories[]" value="Portrait">
        <label for="portrait" class="checkbox-label">Portrait</label><br>
      </fieldset>

      <input class="submitbtn" type="submit" value="Submit">
    </form>
    </div>
    </body>
    
    </html>