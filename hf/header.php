<header>
    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="images/logo.jpg" alt="Optiserve Logo" style="width: 80px; height: auto; border-radius: 20px;">
        <h1><i id="opti">Optiserve</i> - Field Reservation</h1>
    </div>
    <nav>
        <?php
            session_start();
            require_once 'connect.php';

            // Handle logout
            if (isset($_GET['logout'])) {
                session_unset();    
                session_destroy();  
                header("Location: index.php"); 
                exit();
            }

            $is_logged_in = isset($_SESSION['user_email']);
            $user_email = $is_logged_in ? htmlspecialchars($_SESSION['user_email']) : null;
            
            $query = "SELECT name FROM customers WHERE email = ?";
    
           // getting name
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("s", $user_email);
                $stmt->execute();
                $stmt->bind_result($name);
                
                
                if ($stmt->fetch()) {
                    // HI
                } else {
                    $name = "Guest"; 
                }

                $stmt->close();
            }

            if ($is_logged_in): 
        ?>
            <span id="white">Welcome, <?php echo $name; ?></span>
            <a href="profile.php">Profile</a>
            <a href="?logout=true">Sign Out</a>
        <?php else: ?>
            <a href="index.php">Sign In</a>
            <a href="index.php">Sign Up</a>
        <?php endif; ?>
    </nav>
</header>

