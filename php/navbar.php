<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);


    if (!isset($_SESSION["user_id"])) {
        // session_destroy(); // Destroy the session
        // header("Location: php/login.php"); // Redirect to login page
        $noLogIn = true;
        // exit; // Terminate the current script
    } else {
        $user_id = $_SESSION["user_id"];
        $user_role = $_SESSION["role"];
        $userData = User::getById($user_id);
    }

    $ownAccount = false;
    if (isset($_GET['user_id']) && $_GET['user_id'] == $user_id) {
        $ownAccount = true;
    }

    $searchQuery = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

    if(isset($_SESSION["user_id"])){
        $user_id = $_SESSION["user_id"];
        $userData = User::getById($user_id);
    }

?>



<nav class="navbar">
    <div class="navbar__logo">
        <a href="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'index.php' : '../index.php'; ?>">
            Prompt Engine
        </a>
    </div>
    <form method="GET" action="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'php/prompts.php' : 'prompts.php'; ?>">
        <div class="navbar__search">
            <input type="text" placeholder="Search" name="search" class="navbar__search-input" value="<?php echo $searchQuery; ?>">
            <button type="submit" class="navbar__search-button">Search</button>
        </div>
    </form>
    <div class="navbar__buttons">
        <?php if(!empty($userData)) : ?>
        <div class="navbar__button--credit">Credits: <?php echo $userData["credits"] ?></div>
        <?php endif; ?>

        <?php if(!empty($user_id)) : ?>

        <a href="<?php echo strpos($_SERVER['REQUEST_URI'], 'php/') !== false ? '../php/profile.php?user_id='.$user_id : 'php/profile.php?user_id='.$user_id; ?>" class="navbar__button">Profile</a>
            
        <?php else : ?>
        
        <a href="<?php echo strpos($_SERVER['REQUEST_URI'], 'php/') !== false ? '../php/login.php' : 'php/login.php'; ?>" class="navbar__button">Log in</a>
        
        <?php endif; ?>
        
        <?php if (basename($_SERVER['PHP_SELF']) === 'profile.php' && $ownAccount === true) : ?>
            <a href="<?php echo strpos($_SERVER['REQUEST_URI'], 'php/') !== false ? 'profileEdit.php' : 'php/profileEdit.php'; ?>" class="navbar__button">Edit Profile</a>
        <?php endif; ?>
      
        <?php if(isset($user_role)) : ?>
            <?php if ($user_role === "admin") : ?>
                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], 'php/') !== false ? 'roles.php' : 'php/roles.php'; ?>" class="navbar__button">Roles</a>
            <?php endif; ?>
            <?php if ($user_role === "admin" || $user_role === "moderator") : ?>
                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], 'php/') !== false ? 'validate.php' : 'php/validate.php'; ?>" class="navbar__button">Validate</a>
                <?php endif; ?>
        <?php endif; ?>

        <?php
        $currentFile = basename($_SERVER['SCRIPT_NAME']);
        $logoutPath = ($currentFile === 'index.php') ? 'php/logout.php' : 'logout.php';
        ?>

        <a href="<?php echo $logoutPath; ?>" class="navbar__button navbar__button--logout">Log out</a>




    </div>
</nav>

