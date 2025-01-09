<?php
// Database connection
include 'db_connection.php'; // Assuming you've created a separate DB connection file

// Check if field_id is passed as a GET parameter
if (isset($_GET['field_id'])) {
    $field_id = $_GET['field_id'];
    
    // Fetch field details
    $query = "SELECT * FROM fields WHERE field_id = :field_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':field_id', $field_id, PDO::PARAM_INT);
    $stmt->execute();
    $field = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$field) {
        die('Field not found.');
    }
} else {
    die('No field selected.');
}

// Handle form submission for reservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];  // Get customer ID from the session or form
    $reservation_date = $_POST['reservation_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Calculate the total fee based on field and duration
    $total_fee = $field['fees']; // You can implement dynamic fee calculation based on duration

    // Insert reservation into the database
    $query = "INSERT INTO reservations (customer_id, field_id, reservation_date, start_time, end_time, total_fee) 
              VALUES (:customer_id, :field_id, :reservation_date, :start_time, :end_time, :total_fee)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->bindParam(':field_id', $field_id);
    $stmt->bindParam(':reservation_date', $reservation_date);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->bindParam(':total_fee', $total_fee);

    if ($stmt->execute()) {
        echo "<script>alert('Reservation successful!');</script>";
    } else {
        echo "<script>alert('Reservation failed.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Field</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 90%;
            margin: auto;
            padding: 20px;
        }
        header {
            background-color: #007bff;
            padding: 10px 0;
            text-align: center;
            color: white;
        }
        footer {
            background-color: #007bff;
            padding: 10px 0;
            text-align: center;
            color: white;
            margin-top: 20px;
        }
        .field-details {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin: 20px 0;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .field-details img {
            max-width: 300px;
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .field-info {
            flex: 1;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }
        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        form input, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        form button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
            <img src="images/logo.jpg" alt="Optiserve Logo" style="width: 80px; height: auto;">
            <h1>Optiserve - Reserve Field</h1>
        </div>
        <p>Contact us at: <a href="mailto:info@optiserve.com" style="color: white;">info@optiserve.com</a></p>
    </header>

    <!-- Main Content -->
    <div class="container">
        <h2>Reserve Field: <?php echo htmlspecialchars($field['type']); ?></h2>

        <!-- Field Details -->
        <div class="field-details">
            <img src="<?php echo htmlspecialchars($field['image_path']); ?>" alt="Field Image">
            <div class="field-info">
                <h3><?php echo htmlspecialchars($field['type']); ?></h3>
                <p>Fees: <?php echo htmlspecialchars($field['fees']); ?> TL</p>
                <p>Capacity: <?php echo htmlspecialchars($field['capacity']); ?> persons</p>
            </div>
        </div>

        <!-- Reservation Form -->
        <form action="reserve.php?field_id=<?php echo $field['field_id']; ?>" method="POST">
            <label for="reservation_date">Date:</label>
            <input type="date" id="reservation_date" name="reservation_date" required>

            <label for="start_time">Start Time:</label>
            <input type="time" id="start_time" name="start_time" required>

            <label for="end_time">End Time:</label>
            <input type="time" id="end_time" name="end_time" required>

            <input type="hidden" name="customer_id" value="1"> <!-- Replace with dynamic customer ID -->

            <button type="submit">Submit Reservation</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Optiserve | <a href="mailto:info@optiserve.com" style="color: white;">info@optiserve.com</a></p>
        <p>Bio: Optiserve is a leading provider of field reservation systems, ensuring seamless management of sports facilities.</p>
    </footer>
</body>
</html>
