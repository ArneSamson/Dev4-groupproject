<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once("bootstrap.php");

try {
    $conn = Db::getInstance();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the database connection is successful
    if (!$conn) {
        echo "Failed to connect to database!";
        exit;
    }
} catch (PDOException $e) {
    $message = "Try again later: " . $e->getMessage();
    exit($message);
}

$prompts = new Prompts($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    // var_dump($_FILES);

    $prompts->handleUpload($_POST, $_FILES);

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
      <link rel="icon" href="../media/favicon/icon.png" type="image/x-icon"/>

    </head>
    
    <body>
  <!-- <?php include_once("../inc/functions.inc.php"); ?>  -->
  <!-- This is the nav bar -->
  <?php include_once("navbar.php"); ?>

  <div class="form-container">
    <form class="uploadform" enctype="multipart/form-data" method="POST">
      <h2>Upload a new prompt</h2>
      <?php if (isset($error)) : ?> <!-- if error message is set -->
        <p class="errormessage"><?php echo $error ?></p> <!-- display error message -->
      <?php endif; ?>
      <fieldset>
        <legend>Basic Information</legend>

        <div class="form-group">
          <label for="title">Title:</label>
          <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
          <label for="description">Description:</label>
          <textarea style="resize: none;" id="description" name="description" required></textarea>
        </div>

        <div class="form-group">
          <label for="prompt">Prompt:</label>
          <textarea style="resize: none;" id="prompt" name="prompt" required></textarea>
        </div>

        <div class="form-group">
          <label for="price">Price:</label>
          <select id="price" name="price">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>
        </div>

        <div class="form-group">
          <label for="model-type">Model:</label>
          <select id="model-type" name="model-type">
            <option value="dalle">DALL-E</option>
            <option value="midjourney">Midjourney</option>
            <option value="lexica">Lexica</option>
            <option value="stablediffusion">Stable Diffusion</option>
          </select>
        </div>
      </fieldset>

      <fieldset>
        <legend>Image and Tags</legend>
        <div class="form-group">
          <label for="image-upload">Image:</label>
          <input type="file" id="image-upload" name="image-upload" accept="image/*" required>
        </div>

        <div class="form-group">
          <label for="tags">Tags:</label>
          <input type="text" id="tags" name="tags" required>
        </div>
      </fieldset>

      <fieldset>
        <legend>Category</legend>

        <div class="form-group">
          <input type="checkbox" id="animals" name="categories[]" value="Animals">
          <label for="animals" class="checkbox-label">Animals</label>
        </div>

        <div class="form-group">
          <input type="checkbox" id="3D" name="categories[]" value="3D">
          <label for="3D" class="checkbox-label">3D</label>
        </div>
        <div class="form-group">
          <input type="checkbox" id="space" name="categories[]" value="Space">
          <label for="space" class="checkbox-label">Space</label>
        </div>

        <div class="form-group">
          <input type="checkbox" id="game" name="categories[]" value="Game">
          <label for="game" class="checkbox-label">Game</label>
        </div>

        <div class="form-group">
          <input type="checkbox" id="car" name="categories[]" value="Car">
          <label for="car" class="checkbox-label">Car</label>
        </div>

        <div class="form-group">
          <input type="checkbox" id="nature" name="categories[]" value="Nature">
          <label for="nature" class="checkbox-label">Nature</label>
        </div>

        <div class="form-group">
          <input type="checkbox" id="portrait" name="categories[]" value="Portrait">
          <label for="portrait" class="checkbox-label">Portrait</label>
        </div>
      </fieldset>

      <div class="form-group">
        <input class="submitbtn" type="submit" value="Submit">
      </div>
    </form>
  </div>
</body>
    
</html>