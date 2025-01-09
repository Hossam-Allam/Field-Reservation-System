<?php

session_start();

// Database connection
require_once 'connect.php';

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_email']);
$user_email = $is_logged_in ? htmlspecialchars($_SESSION['user_email']) : null;

// Fetch fields from the database
$query = "SELECT * FROM fields WHERE availability = 1"; // Show only available fields
$result = $conn->query($query);

if ($result === false) {
    die("Database query failed: " . $conn->error);
}

$fields = [];
while ($row = $result->fetch_assoc()) {
    $fields[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Reservation</title>
    <link rel="stylesheet" href="styles/homepage.css">

</head>
<body>
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

    <div class="container">
        <h2>Available Fields</h2>
        <?php if (count($fields) > 0): ?>
            <div class="field-list">
                <?php foreach ($fields as $field): ?>
                    <div class="field-item">
                        <img src="<?php echo htmlspecialchars($field['image_path']); ?>" alt="Field Image" class="field-image">
                        <h3><?php echo htmlspecialchars($field['type']); ?></h3>
                        <p>Fees: <?php echo htmlspecialchars($field['fees']); ?> TL</p>
                        <p>Capacity: <?php echo htmlspecialchars($field['capacity']); ?> persons</p>
                        <a href="reserve.php?field_id=<?php echo htmlspecialchars($field['field_id']); ?>" class="reserve-btn">Reserve Now</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No fields are available at the moment. Please check back later.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2025 Optiserve | <a href="mailto:info@optiserve.com" style="color: white;">info@optiserve.com</a></p>
        <p>Bio: Optiserve is a leading provider of field reservation systems, ensuring seamless management of sports facilities.</p>
    </footer>
</body>
</html>

