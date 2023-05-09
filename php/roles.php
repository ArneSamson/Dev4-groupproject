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

    $user = User::getByUsername($username);

    if (!$user) {
        $errorMessage = "User not found.";
    } else {
        $user->setRole($role);
        if ($user->updateUser($user_id, $user->getUsername(), $user->getEmail(), '', $role)) {
            $successMessage = "User role updated successfully.";
        } else {
            $errorMessage = "Failed to update user role.";
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Roles</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar__logo">Prompt Engine</div>
        <div class="navbar__buttons">
            <a href="index.php">Home</a>
            <a href="logout.php" class="navbar__button navbar__button--logout">Log out</a>
        </div>
    </nav>

    <div class="container">
        <h1>User Roles</h1>

        <?php if (isset($successMessage)) : ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($errorMessage)) : ?>
            <div class="message error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Edit Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td>
                            <form method="POST" action="">
                            <input type="hidden" name="user_id[]" value="<?php echo $user['id']; ?>">
                            <input type="hidden" name="username[]" value="<?php echo $user['username']; ?>">

                                <select name="role">
                                    <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                                </select>
                                <button type="submit">Edit</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>