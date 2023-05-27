<?php
require_once 'bootstrap.php';
include_once("../inc/functions.inc.php");

// Check if the user is logged in and has admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get all users and their roles
$users = User::getAllUsers();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];

    // Update the user role in the database
    $conn = Db::getInstance();
    $statement = $conn->prepare("UPDATE users SET role = :role WHERE id = :id");
    $statement->bindValue(":role", $role);
    $statement->bindValue(":id", $user_id);
    $result = $statement->execute();

    if ($result) {
        $successMessage = "User role updated successfully.";
    } else {
        $errorMessage = "Failed to update user role.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Roles</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../media/favicon/icon.png" type="image/x-icon"/>

</head>
<body>
    <?php include_once("navbar.php"); ?>

    <div class="container">
        <h1 class="page-title">User Roles</h1>

        <?php if (isset($successMessage)) : ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($errorMessage)) : ?>
            <div class="message error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <table class="user-table">
            <thead>
                <tr>
                    <th class="user-table__header">Edit roles</th>
            
                </tr>
            </thead>
            <tbody class="validate-tbody">
                <?php foreach ($users as $user) : ?>
                    <tr class="user-table__row">
                        <td class="user-table__data"><?php echo $user['id']; ?></td>
                        <td class="user-table__data"><?php echo $user['username']; ?></td>
                        <td class="user-table__data"><?php echo $user['role']; ?></td>
                        <td class="user-table__data">
                            <form method="post">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <select name="role" class="user-table__select">
                                    <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                                    <option value="moderator" <?php echo ($user['role'] === 'moderator') ? 'selected' : ''; ?>>Moderator</option>
                                    <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                </select>
                                <button type="submit" class="user-table__button">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>
