<?php
    session_start();
    require_once 'connect.php';

    $user_email = $_SESSION['user_email']; 

    
    $query = "SELECT customer_id FROM customers WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $user_email); // Use 's' for strings
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        $customer_id = $row['customer_id']; 
    } else {
        echo "User not found.";
    }

    // Checking if field_id is passed as a GET parameter
    if (isset($_GET['field_id'])) {
        $field_id = $_GET['field_id'];

        
        $query = "SELECT * FROM fields WHERE field_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $field_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $field = $result->fetch_assoc();

        if (!$field) {
            die('Field not found.');
        }
    } else {
        die('No field selected.');
    }

    // Handle form submission for reservation
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reservation_date = $_POST['reservation_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        // fee calculation
        $start_time_obj = DateTime::createFromFormat('H:i', $_POST['start_time']);
        $end_time_obj = DateTime::createFromFormat('H:i', $_POST['end_time']);

        
        $duration = $start_time_obj->diff($end_time_obj);
        $duration_in_hours = $duration->h + ($duration->i / 60);

        
        $total_fee = $field['fees'] * $duration_in_hours;

        
        $query = "INSERT INTO reservations (customer_id, field_id, reservation_date, start_hour, end_hour, total_fee) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('iisssd', $customer_id, $field_id, $reservation_date, $start_time, $end_time, $total_fee);

        if ($stmt->execute()) {
            echo "Reservation successful!";
        } else {
            echo "Reservation failed: " . $conn->error;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Field</title>
    <link rel="stylesheet" href="styles/styles.css">
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


