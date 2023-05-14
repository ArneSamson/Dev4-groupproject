<?php
include_once("bootstrap.php");

// Check if the user is an admin or moderator
if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["role"], ['admin', 'moderator'])) {
    header("Location: login.php");
    exit;
}

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

$promptsList = $prompts->getPromptsForValidation();

// Handle button actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["validate"])) {
        $promptId = $_POST["validate"];
        $prompts->validatePrompt($promptId);
        // Perform any additional actions if necessary
    } elseif (isset($_POST["invalidate"])) {
        $promptId = $_POST["invalidate"];
        $prompts->invalidatePrompt($promptId);
        // Perform any additional actions if necessary
    }
}

?>


<table>
    <thead>
        <tr>
            <th>Prompt ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Validate</th>
            <th>Invalidate</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($promptsList as $prompt) : ?>
            <tr>
                <td><?php echo $prompt['id']; ?></td>
                <td><?php echo $prompt['name']; ?></td>
                <td><?php echo $prompt['description']; ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="validate" value="<?php echo $prompt['id']; ?>">
                        <button type="submit">Validate</button>
                    </form>
                </td>
                <td>
                    <form method="post">
                        <input type="hidden" name="invalidate" value="<?php echo $prompt['id']; ?>">
                        <button type="submit">Invalidate</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
