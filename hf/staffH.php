<header>
    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="images/logo.jpg" alt="Optiserve Logo" style="width: 80px; height: auto; border-radius: 20px;">
        <h1><i id="opti">Optiserve</i> - Field Reservation</h1>
    </div>
    <nav>
        <?php
            session_start();

            // Handle logout
            if (isset($_GET['logout'])) {
                session_unset();    
                session_destroy();  
                header("Location: index.php"); 
                exit();
            }

            $is_logged_in = isset($_SESSION['staff_email']);
            $staff_email = $is_logged_in ? htmlspecialchars($_SESSION['staff_email']) : null;

            if ($is_logged_in): 
        ?>
            <span id="white">Welcome, <?php echo $staff_email; ?></span>
            <a href="?logout=true">Sign Out</a>
        <?php else: ?>
            <a href="index.php">Sign In</a>
            <a href="index.php">Sign Up</a>
        <?php endif; ?>
    </nav>
</header>