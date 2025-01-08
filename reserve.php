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
        echo "Reservation successful!";
    } else {
        echo "Reservation failed.";
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
</head>
<body>
    <div class="container">
        <h1>Reserve Field: <?php echo htmlspecialchars($field['type']); ?></h1>
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
</body>
</html>
