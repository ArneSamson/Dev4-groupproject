<?php
require_once 'bootstrap.php';
include_once("../inc/functions.inc.php");


// Make sure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the current user's information
$user_id = $_SESSION['user_id'];
$conn = Db::getInstance();
$statement = $conn->prepare("SELECT * FROM users WHERE id = :id");
$statement->bindValue(":id", $user_id);
$statement->execute();
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if (!empty($_POST)) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if a file was uploaded
    if (!empty($_FILES['profile_picture']['name'])) {
        // Define the directory where the uploaded image will be stored
        $target_dir = "../media/pfp/";
        // Generate a unique filename for the uploaded image
        $filename = uniqid() . "-" . basename($_FILES["profile_picture"]["name"]);
        // Define the full path to the uploaded image file
        $target_file = $target_dir . $filename;
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Set the profile picture URL in the user's record in the database
            $profile_picture_url = "/media/pfp/$filename";  // Replace with your actual domain name and directory path
            $user['profile_picture_url'] = $profile_picture_url;
        } else {
            $error = true;
        }
    }

    if (updateUser($user_id, $username, $email, $password, $conn, $target_file)) {
        $success = true;
    } else {
        $error = true;
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="form">
        <h2 class="form__title">Edit Account</h2>

        <?php if (isset($success)): ?>
            <div class="form__success">
                <p>Account updated successfully!</p>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="form__error">
                <p>Error updating account.</p>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form__field">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form__field">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form__field">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Leave blank to keep current password">
            </div>
            <div class="form__field">
                <input type="submit" value="Update" class="form__button">
            </div>
            <div class="form__field">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" name="profile_picture" id="profile_picture">
            </div>

        </form>
