<?php
// Define a Prompts class


class Prompts
{
    private $conn;
    private $name;
    private $description;
    private $model;
    private $price;
    private $prompt;
    private $tags;
    private $fileName;
    private $fileTempName;
    private $fileSize;
    private $fileError;
    private $fileType;
    private $selectedCategories;
    private $onlin;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public static function getPromptsBySearchQuery($searchQuery) {
        try {
            $conn = Db::getInstance();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $prompts = array();

            if (!empty($searchQuery)) {
                $search = '%' . strtolower($searchQuery) . '%';
                $query = $conn->prepare("SELECT * FROM prompts WHERE onlin = 1 AND LOWER(name) LIKE :search");
                $query->bindValue(":search", $search);
                $query->execute();
                $prompts = $query->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $query = $conn->prepare("SELECT * FROM prompts WHERE onlin = 1");
                $query->execute();
                $prompts = $query->fetchAll(PDO::FETCH_ASSOC);
            }

            return $prompts;
        } catch (PDOException $e) {
            $message = "Try again later: " . $e->getMessage();
            exit;
        }
    }

    public static function getFilteredPrompts($searchQuery, $selectedModels, $selectedCategories, $sortBy)
{
    try {
        $conn = Db::getInstance();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $prompts = array();

        $query = "SELECT * FROM prompts WHERE onlin = 1";

        if (!empty($searchQuery)) {
            $search = '%' . strtolower($searchQuery) . '%';
            $query .= " AND LOWER(name) LIKE :search";
        }

        if (!empty($selectedModels) && !in_array("all", $selectedModels)) {
            $models = implode("', '", $selectedModels);
            $query .= " AND model IN ('$models')";
            var_dump($query);
        }

        if (!empty($selectedCategories) && !in_array("all", $selectedCategories)) {
            $categories = implode("','", $selectedCategories);
            $query .= " AND categories IN ('$categories')";
        }

        // Sort the results
        if ($sortBy === "name") {
            $query .= " ORDER BY name";
        } elseif ($sortBy === "price_up") {
            $query .= " ORDER BY price ASC";
        } elseif ($sortBy === "price_down") {
            $query .= " ORDER BY price DESC";
        }

        $stmt = $conn->prepare($query);

        if (!empty($searchQuery)) {
            $stmt->bindValue(":search", $search);
        }

        $stmt->execute();
        $prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $prompts;
    } catch (PDOException $e) {
        $message = "An error occurred: " . $e->getMessage();
        error_log($message); // Log the error message to the PHP error log
        exit($message); // Display the specific error message
    }
    
}
    

    // Getters and Setters
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getOnlin()
    {
        return $this->onlin;
    }

    public function setOnlin($onlin)
    {
        $this->onlin = $onlin;
    }

    public function getPrompt()
    {
        return $this->prompt;
    }

    public function setPrompt($prompt)
    {
        $this->prompt = $prompt;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    }

    public function getFileType()
    {
        return $this->fileType;
    }

    public function getFileTempName()
    {
        return $this->fileTempName;
    }

    public function setFileTempName($fileTempName)
    {
        $this->fileTempName = $fileTempName;
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    public function getFileError()
    {
        return $this->fileError;
    }

    public function setFileError($fileError)
    {
        $this->fileError = $fileError;
    }

    public function getSelectedCategories()
    {
        return $this->selectedCategories;
    }

    public function setSelectedCategories($selectedCategories)
    {
        $this->selectedCategories = $selectedCategories;
    }

    public function handleUpload()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->name = $_POST["title"];
            $this->description = $_POST["description"];
            $this->model = $_POST["model-type"];
            $this->price = $_POST["price"];
            $this->prompt = $_POST["prompt"];
            $this->tags = $_POST["tags"];

            // Handle uploaded file
            $this->fileName = $_FILES["image-upload"]["name"];
            $this->fileTempName = $_FILES["image-upload"]["tmp_name"];
            $this->fileSize = $_FILES["image-upload"]["size"];
            $this->fileError = $_FILES["image-upload"]["error"];

            if ($this->fileError !== UPLOAD_ERR_OK) {
                $message = "Upload failed with error code $this->fileError.";
                exit;
            }
            

            // Check file size
            if ($this->fileSize > 1000000) {
                $message = "File is too big"; // set error message
                header("Location: ../php/upload.php?error=" . urlencode($message)); // redirect to edit account page
                exit;
            }

            // Check file name for invalid characters
            if (!preg_match('/^[a-zA-Z0-9_]+\.[a-zA-Z0-9]{3,4}$/', $this->fileName)) {
                $message = "File name is not correct"; // set error message
                header("Location: ../php/upload.php?error=" . urlencode($message)); // redirect to edit account page
                exit;
            }

            // Save uploaded file to disk
            $uploadsDir = "..\\media\\";
            $fileName = basename($this->fileTempName);
            $filePath = $uploadsDir . $fileName;

            if (!move_uploaded_file($this->fileTempName, $filePath)) {
                $message = "Failed to move uploaded file.";
                exit;
            }

            $categories = array("Animals", "3D", "Space", "Game", "Car", "Nature", "Portrait", "Anime", "Interior", "Realistic", "Geek", "Building");
            $this->selectedCategories = array();
            foreach ($categories as $category) {
                if (isset($_POST["categories"]) && in_array($category, $_POST["categories"])) {
                    $this->selectedCategories[] = $category;
                }
            }
            $categoriesStr = implode(",", $this->selectedCategories);

            // Insert data into the database, including image file name
            $currentDate = date("Y-m-d H:i:s");

            $query = $this->conn->prepare("INSERT INTO prompts (user_id, name, prompt, description, date, pictures, categories, price, tags, model) VALUES (:user_id, :name, :prompt, :description, :date, :pictures, :categories, :price, :tags, :model)");
            $query->bindValue(":user_id", $_SESSION["user_id"]);
            $query->bindValue(":name", $this->name);
            $query->bindValue(":prompt", $this->prompt);
            $query->bindValue(":description", $this->description);
            $query->bindValue(":date", $currentDate);
            $query->bindValue(":pictures", $filePath);
            $query->bindValue(":categories", $categoriesStr);
            $query->bindValue(":price", $this->price);
            $query->bindValue(":tags", $this->tags);
            $query->bindValue(":model", $this->model);
            $query->execute();

        }
        
    }

    
    public function validatePrompt($promptId)
    {
        $query = $this->conn->prepare("UPDATE prompts SET onlin = 1 WHERE id = :id");
        $query->bindValue(":id", $promptId);
        $query->execute();
    }

    public function invalidatePrompt($promptId)
    {
        $query = $this->conn->prepare("DELETE FROM prompts WHERE id = :id");
        $query->bindValue(":id", $promptId);
        $query->execute();
    }

    public function searchPrompts($searchQuery)
    {
        $query = $this->conn->prepare("SELECT * FROM prompts WHERE onlin = 1 AND name LIKE :search");
        $query->bindValue(":search", "%$searchQuery%");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPromptsForValidation()
{
    try {
        $query = $this->conn->prepare("SELECT * FROM prompts WHERE onlin = 0");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = "Try again later: " . $e->getMessage();
        exit;
    }
}
    

}
