<nav class="navbar">
    <div class="navbar__logo">
        <a href="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'index.php' : '../index.php'; ?>">
            Prompt Engine
        </a>
    </div>
    <form method="GET" action="">
        <div class="navbar__search">
            <form action="index.php" method="GET">
                <input type="text" placeholder="Search" name="search" class="navbar__search-input" value="<?php echo $searchQuery; ?>">
                <button type="submit" class="navbar__search-button">Search</button>
            </form>
        </div>
    </form>
    <div class="navbar__buttons">
        <div class="navbar__button--credit">Credits: 0</div>
        <a href="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'profile.php?user_id='.$user_id : 'php/profile.php?user_id='.$user_id; ?>" class="navbar__button">Edit Profile</a>
        <?php if ($user_role === "admin") : ?>
            <a href="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'php/roles.php' : '../roles.php'; ?>" class="navbar__button">Roles</a>
        <?php endif; ?>
        <?php if ($user_role === "admin" || $user_role === "moderator") : ?>
            <a href="<?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'php/validate.php' : '../validate.php'; ?>" class="navbar__button">Validate</a>
        <?php endif; ?>
        <a href="?logout=true" class="navbar__button navbar__button--logout">Log out</a>
    </div>
</nav>
