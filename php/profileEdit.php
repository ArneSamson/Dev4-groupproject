    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);


    include_once('bootstrap.php');
    include_once("../inc/functions.inc.php");

    // // Make sure the user is logged in
    // if (!isset($_SESSION['user_id'])) {
    //     header("Location: login.php");
    //     exit();
    // }

    // Get the current user's information
    $user_id = $_SESSION['user_id'];
    $user = new User();
    $data = $user->getById($user_id);

    
    // Handle form submission
    if (!empty($_POST)) {
        $password = $_POST['password'];
        $isTaken = $user->isUsernameTaken($_POST['username']);

        // Validate the password field
        if (empty($password)) {
            $error = true;
            $errorMessage = "Password is required.";
        } else {

            if($isTaken == true && $_POST['username'] != $data['username']){
                $error = true;
                $errorMessage = "Username is already taken.";
            }else{


                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                    $user->checkUploadedFile($_FILES['profile_picture']);
                } else {
                    $filePath = user::getProfilePictureUrlById($user_id);
                    $user->setProfilePicture($filePath);
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
                
                if ($user->updateUser($password)) {
                    $success = true;
                } else {
                    $error = true;
                    $errorMessage = "Error updating account.";
                }
            }
        }

    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Edit Account</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../media/favicon/icon.png" type="image/x-icon"/>

    </head>
    <body>
        
        <?php if(!isset($_SESSION['user_id'])): ?>
            <?php header("Location: login.php"); ?>
            
        <?php else: ?>
            <?php include_once("navbar.php"); ?>

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
                    <img src="<?php echo $data['imagepath']; ?>" alt="profilepicture" style="width: 100px; height: auto">
                </div>
                <div class="form__field">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" value="<?php echo $data['username']; ?>" required>
                </div>
                <div class="form__field">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo $data['email']; ?>" required>
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

        <?php endif; ?>
    </body>
    </html>
