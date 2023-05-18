<?php
if (!isset($_SESSION["user_id"])) {
    $user_id = "";
} else {
    $user_id = $_SESSION["user_id"];
    $user_role = $_SESSION["role"];
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: php/login.php");
    exit;
}

$searchQuery = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
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
        <div class="navbar__button--credit">Credits: 0</div>
        <a href="<?php echo strpos($_SERVER['REQUEST_URI'], 'php/') !== false ? '../php/profile.php?user_id='.$user_id : 'php/profile.php?user_id='.$user_id; ?>" class="navbar__button">Profile</a>
      
        <!-- <?php echo $_GET['user_id']?>
        <?php echo $user_id?> -->
        
        <?php if (basename($_SERVER['PHP_SELF']) === 'profile.php') : ?>
            <a href="<?php echo strpos($_SERVER['REQUEST_URI'], 'php/') !== false ? 'profileEdit.php' : 'php/profileEdit.php'; ?>" class="navbar__button">Edit Profile</a>
        <?php endif; ?>
      
        <?php if ($user_role === "admin") : ?>
            <a href="<?php echo strpos($_SERVER['REQUEST_URI'], 'php/') !== false ? 'roles.php' : 'php/roles.php'; ?>" class="navbar__button">Roles</a>
        <?php endif; ?>
        <?php if ($user_role === "admin" || $user_role === "moderator") : ?>
            <a href="<?php echo strpos($_SERVER['REQUEST_URI'], 'php/') !== false ? 'validate.php' : 'php/validate.php'; ?>" class="navbar__button">Validate</a>
        <?php endif; ?>
        <a href="?logout=true" class="navbar__button navbar__button--logout">Log out</a>
    </div>
</nav>

