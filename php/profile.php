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

    $target_dir = "../media/pfp/";

    if (!empty($_FILES['profile_picture']['name'])) {
        $image_file = $_FILES['profile_picture']['name'];
        $image_tmp = $_FILES['profile_picture']['tmp_name'];
        $image_extension = pathinfo($image_file, PATHINFO_EXTENSION);
        $image_name = uniqid() . '.' . $image_extension;
        $target_file = $target_dir . $image_name;
        move_uploaded_file($image_tmp, $target_file);
    } else {
        $target_file = $user['imagepath'];
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
                <p>Error updating account.</p>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form__field">
                <img src="<?php echo $user['imagepath']?>" alt="profilepicture" style="width: 100px; height: auto">
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
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Leave blank to keep current password">
            </div>
            <div class="form__field">
                <input type="submit" value="Update" class="form__button">
            </div>
            <div class="form__field">
                <label for="profile_picture">Set new profile picture:</label>
                <input type="file" name="profile_picture" id="profile_picture">
            </div>
        </form>
    </div>
</body>
</html>