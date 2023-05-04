<?php
    include_once(__DIR__ . '/bootstrap.php');
    
    if (isset($_GET["error"])) {
      $error = $_GET["error"];
    }
    
    // if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    //   header('Location: ../php/login.php');
    //   exit;
    // }
    
    $user_id = $_SESSION["user_id"];
 
    
    try {
      $conn = Db::getInstance();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      $message = "Try again later: " . $e->getMessage();
      exit;
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $name = $_POST["title"];
      $description = $_POST["description"];
      $model = $_POST["model-type"];
      $price = $_POST["price"];
      $prompt = $_POST["prompt"];
      $tags = $_POST["tags"];
    
      // Handle uploaded file
      $file_name = $_FILES["image-upload"]["name"];
      $file_temp_name = $_FILES["image-upload"]["tmp_name"];
      $file_size = $_FILES["image-upload"]["size"];
      $file_error = $_FILES["image-upload"]["error"];
    
      if ($file_error !== UPLOAD_ERR_OK) {
        $message = "Upload failed with error code $file_error.";
        exit;
      }
    
      // Check file size
      if ($file_size > 1000000) {
        $message = "File is too big"; // set error message
        header("Location: ../php/upload.php?error=" . urlencode($message)); // redirect to edit account page
        exit;
      }
    
      // Check file name for invalid characters
      if (!preg_match('/^[a-zA-Z0-9_]+\.[a-zA-Z0-9]{3,4}$/', $file_name)) {
        $message = "File name is not correct"; // set error message
        header("Location: ../php/upload.php?error=" . urlencode($message)); // redirect to edit account page
        exit;
      }
    
      // Save uploaded file to disk
      $uploads_dir = "../media/";
      $file_path = $uploads_dir . $file_name;
      if (!move_uploaded_file($file_temp_name, $file_path)) {
        $message = "Failed to move uploaded file.";
        exit;
      }
    
      $categories = array("Animals", "3D", "Space", "Game", "Car", "Nature", "Portrait", "Anime", "Interior", "Realistic", "Geek", "Building");
      $selected_categories = array();
      foreach ($categories as $category) {
        if (isset($_POST["categories"]) && in_array($category, $_POST["categories"])) {
          $selected_categories[] = $category;
        }
      }
      $categories_str = implode(", ", $selected_categories);
    
      // Insert data into database, including image file name
      $current_date = date("Y-m-d H:i:s");
    
      $query = $conn->prepare("INSERT INTO prompts (name, user, description, model, pictures, characteristics, price, prompt, tags, date) VALUES (:name, :email, :description, :model, :pictures, :categories, :price, :prompt, :tags, :date)");
      $query->bindValue(":name", $name);
      $query->bindValue(":email", $email);
      $query->bindValue(":description", $description);
      $query->bindValue(":model", $model);
      $query->bindValue(":pictures", $file_name);
      $query->bindValue(":categories", implode(", ", $selected_categories));
      $query->bindValue(":price", $price);
      $query->bindValue(":prompt", $prompt);
      $query->bindValue(":tags", $tags);
      $query->bindValue(":date", $current_date); // Bind the current date to the query
      $query->execute();
    
      header('Location: ../php/succes.php');
    }
    
    // Retrieve data from database, including image file name
    $query = $conn->prepare("SELECT * FROM prompts WHERE user_id = :user_id");
    $query->bindValue(":user_id", $user_id);
    $query->execute();
    $prompts = $query->fetchAll(PDO::FETCH_ASSOC);
    
    ?>
    
    <!DOCTYPE html>
    <html lang="en">
    
    <head>
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>upload your prompt</title>
      <link rel="stylesheet" href="https://use.typekit.net/kqy0ynu.css" />
      <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>" />
    </head>
    
    <body>
      <!-- <?php include_once("../inc/functions.inc.php"); ?>  -->
      <!-- This is the nav bar -->
    
    
      <form class="uploadform" enctype="multipart/form-data" method="POST">
        <h2>Upload a new prompt</h2>
        <?php if (isset($error)) : ?> <!-- if error message is set -->
          <p class="errormessage"><?php echo $error ?></p> <!-- display error message -->
        <?php endif; ?>
    
        <label for="title">Title</label><br>
        <input class="inputfield" type="text" id="title" name="title" required><br><br>
    
        <label for="description">Description</label><br>
        <textarea style="resize: none;" class="inputfield2" id="description" name="description" required></textarea><br><br>
    
        <label for="description">Prompt</label><br>
        <textarea style="resize: none;" class="inputfield2" id="prompt" name="prompt" required></textarea><br><br>
    
        <label for="description">Price</label><br>
        <select class="inputfield3" id="price" name="price">
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select><br><br>
    
        <label for="model-type">Model</label><br>
        <select class="inputfield3" id="model-type" name="model-type">
          <option value="dalle">DALL-E</option>
          <option value="midjourney">Midjourney</option>
          <option value="lexica">Lexica</option>
          <option value="stablediffusion">Stable Diffusion</option>
        </select><br><br>
    
        <label for="image-upload">Image</label>
        <input class="inputfield4" type="file" id="image-upload" name="image-upload" accept="image/*" required><br><br>
    
        <label for="title">Tags</label><br>
        <input class="inputfield" type="text" id="tags" name="tags" required><br><br>
    
    
        <p class="filter">Category:</p>
    
        <input type="checkbox" id="animals" name="categories[]" value="Animals">
        <label for="animals"> Animals</label><br>
    
        <input type="checkbox" id="3D" name="categories[]" value="3D">
        <label for="3D"> 3D</label><br>
    
        <input type="checkbox" id="space" name="categories[]" value="Space">
        <label for="space"> Space</label><br>
    
        <input type="checkbox" id="game" name="categories[]" value="Game">
        <label for="game"> Game</label><br>
    
        <input type="checkbox" id="car" name="categories[]" value="Car">
        <label for="car"> Car</label><br>
    
        <input type="checkbox" id="nature" name="categories[]" value="Nature">
        <label for="nature"> Nature</label><br>
    
        <input type="checkbox" id="portrait" name="categories[]" value="Portrait">
        <label for="portrait"> Portrait</label><br>
    
        <input type="checkbox" id="anime" name="categories[]" value="Anime">
        <label for="anime"> Anime</label><br>
    
        <input type="checkbox" id="interior" name="categories[]" value="Interior">
        <label for="interior"> Interior</label><br>
    
        <input type="checkbox" id="realistic" name="categories[]" value="Realistic">
        <label for="realistic"> Realistic</label><br>
    
        <input type="checkbox" id="geek" name="categories[]" value="Geek">
        <label for="geek"> Geek</label><br>
    
        <input type="checkbox" id="building" name="categories[]" value="Building">
        <label for="building"> Building</label><br>
    
        <input class="submitbtn" type="submit" value="Submit">
      </form>
    
    
      <!-- <?php include_once("../inc/foot.inc.php"); ?> This is the footer -->
    </body>
    
    </html>