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
} catch (PDOException $e) {
    $message = "Try again later: " . $e->getMessage();
    exit;
}

$prompts = new Prompts($conn);

// Retrieve prompts that need validation
$query = $conn->prepare("SELECT * FROM prompts WHERE onlin = 0");
$query->execute();
$promptsList = $query->fetchAll(PDO::FETCH_ASSOC);

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

// Display the prompts in a table
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
