<?php
    session_start();
    require_once 'connect.php';



    if (!isset($_SESSION['user_email'])) {
        die("You must be logged in to view this page.");
    }

    $user_email = $_SESSION['user_email'];

    try {
        // Fetch customer details
        $query = "SELECT * FROM customers WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        if (!$customer) {
            die("Customer not found.");
        }

        // Fetch reservations
        $query = "SELECT * FROM reservations r 
                JOIN fields f ON r.field_id = f.field_id 
                WHERE r.customer_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $customer['customer_ID']);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservations = $result->fetch_all(MYSQLI_ASSOC);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile</title>
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        .container { margin: auto; padding: 20px; max-width: 800px; }
        .profile-info, .reservations { margin-bottom: 20px; }
        .reservations .reservation { border: 1px solid #ddd; border-radius: 5px; padding: 10px; margin-bottom: 10px; }
        .edit-btn { background: #007bff; color: #fff; padding: 5px 10px; border-radius: 5px; text-decoration: none; }
        .edit-btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($customer['name']); ?>!</h1>
    </header>
    <div class="container">
        <div class="profile-info">
            <h2>Personal Details</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($customer['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($customer['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($customer['phone']); ?></p>
            <a href="edit_profile.php" class="edit-btn">Edit Profile</a>
        </div>
        <div class="reservations">
            <h2>Your Reservations</h2>
            <?php if (!empty($reservations)): ?>
                <?php foreach ($reservations as $reservation): ?>
                    <div class="reservation">
                        <p><strong>Field:</strong> <?php echo htmlspecialchars($reservation['type']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($reservation['reservation_date']); ?></p>
                        <p><strong>Time:</strong> <?php echo htmlspecialchars($reservation['start_hour']); ?> - <?php echo htmlspecialchars($reservation['end_time']); ?></p>
                        <p><strong>Fees:</strong> <?php echo htmlspecialchars($reservation['total_fee']); ?> TL</p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reservations found.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Optiserve | Contact: <a href="mailto:info@optiserve.com">info@optiserve.com</a></p>
    </footer>
</body>
</html>