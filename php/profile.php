<?php
    //a screen where a use can see his login info
    //and change it
    //and delete his account

    require_once 'bootstrap.php';

    // Make sure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
    
    // Handle the form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validate the form data
        // ...
    
        // Update the user's account details
        // ...
    
        // Redirect back to the user's profile page
        header('Location: profile.php');
        exit();
    }
    
    // Get the user's account details from the database
    $user = User::getById($_SESSION['user_id']);
    
    // Display the user's account details and the account update form
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Profile</title>
    </head>
    <body>
        <h1>Profile</h1>
    
        <p>Welcome, <?php echo $user->getName(); ?>!</p>
    
        <h2>Account details</h2>
    
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $user->getUsername(); ?>"><br>
    
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user->getEmail(); ?>"><br>
    
            <button type="submit">Update account</button>
        </form>
    
        <h2>Delete account</h2>
    
        <form method="post" action="delete-account.php">
            <p>Are you sure you want to delete your account?</p>
    
            <button type="submit">Delete account</button>
        </form>
    </body>
    </html>
    
    
