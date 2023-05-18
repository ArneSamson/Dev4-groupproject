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
    $role = $user['role'];

    // Validate the password field
    if (empty($password)) {
        $error = true;
        $errorMessage = "Password is required.";
    } else {
        // Check if a profile picture is uploaded
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $profilePicture = $_FILES['profile_picture']['tmp_name'];
        } else {
            $profilePicture = null;
        }

        // Handle delete account request
        if (isset($_POST['delete_account'])) {
            $confirmation = $_POST['confirmation']; // Get the value of the confirmation checkbox
    
            if ($confirmation == '1') {
                deleteAccount($_SESSION['user_id']); // Call the deleteAccount function to delete the user account
            } else {
                $error = true;
                $errorMessage = "Please confirm deletion by checking the box.";
            }
        }
        
        if (updateUser($user_id, $username, $email, $password, $role, $conn, $profilePicture)) {
            $success = true;
        } else {
            $error = true;
            $errorMessage = "Error updating account.";
        }
        
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
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
                <p><?php echo $errorMessage; ?></p>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">

            <div class="form__field">
                <img src="<?php echo $user['imagepath']; ?>" alt="profilepicture" style="width: 100px; height: auto">
            </div>
            <div class="form__field">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form__field">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form__field">
                <label for="password">Password: *</label>
                <input type="password" name="password" id="password" placeholder="required field">
            </div>
            <div class="form__field">
                <label for="profile_picture">Set new profile picture:</label>
                <input type="file" name="profile_picture" id="profile_picture">
            </div>

            <!-- update button -->
            <div class="form__field">
                <input type="submit" value="Update" class="form__button">
            </div>

            <div class="form__field">
                <label for="confirmation">Confirm Deletion:</label>
                <input type="checkbox" name="confirmation" id="confirmation" value="1">
                <label for="confirmation">I confirm that I want to delete my account</label>
            </div>
            <div class="form__field">
                <label>Delete Account:</label>
                <button type="submit" name="delete_account">Delete</button>
            </div>

        </form>
    </div>
</body>
</html>
