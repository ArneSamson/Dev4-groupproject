<?php

// Define a Prompts class
class Prompts
{
    private $conn;  

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function handleUpload()
    {
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

            $query = $this->conn->prepare("INSERT INTO prompts (user_id, name, prompt, description, date, pictures, categories, price, tags, model) VALUES (:user_id, :name, :prompt, :description, :date, :pictures, :categories, :price, :tags, :model)");
            $query->bindValue(":user_id", $_SESSION["user_id"]);
            $query->bindValue(":name", $name);
            $query->bindValue(":prompt", $prompt);
            $query->bindValue(":description", $description);
            $query->bindValue(":date", $current_date);
            $query->bindValue(":pictures", $file_name);
            $query->bindValue(":categories", $categories_str);
            $query->bindValue(":price", $price);
            $query->bindValue(":tags", $tags);
            $query->bindValue(":model", $model);
            $query->execute();

            header('Location: ../php/success.php');
        }
    }

    public function getPromptsByUserId($user_id)
    {
        $query = $this->conn->prepare("SELECT * FROM prompts WHERE user_id = :user_id");
        $query->bindValue(":user_id", $user_id);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
};