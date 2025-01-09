<header>
    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="images/logo.jpg" alt="Optiserve Logo" style="width: 80px; height: auto; border-radius: 20px;">
        <h1><i>Optiserve</i> - Field Reservation</h1>
    </div>
    <nav>
        <?php if ($is_logged_in): ?>
            <span>Welcome, <?php echo $user_email; ?></span>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Sign Out</a>
        <?php else: ?>
            <a href="signin.php">Sign In</a>
            <a href="signup.php">Sign Up</a>
        <?php endif; ?>
    </nav>
</header>
