<?php
    session_start();
    require_once 'connect.php';

    $is_logged_in = isset($_SESSION['user_email']);
    $user_email = $is_logged_in ? htmlspecialchars($_SESSION['user_email']) : null;

    
    $query = "SELECT customer_id FROM customers WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $user_email); 
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
            echo '<script>alert("Reservation Successful");</script>';
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&family=Lilita+One&family=Oswald:wght@200..700&family=Tiny5&display=swap" rel="stylesheet">
    <title>Reserve Field</title>
    <link rel="stylesheet" href="styles/reserve.css">
    <link rel="stylesheet" href="styles/HFstyles.css">
</head>
<body>

    <?php include('header.php'); ?>

    <div class="container">
        <h1>Reservation for <span><?php echo htmlspecialchars($field['type']); ?> </span> Field </h1>
        <form action="reserve.php?field_id=<?php echo $field['field_id']; ?>" method="POST">

            <div class="outer">

                <div id="inner">
                    <label for="reservation_date">Date:</label>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>calendar-month-outline</title><path d="M7 11H9V13H7V11M21 5V19C21 20.11 20.11 21 19 21H5C3.89 21 3 20.1 3 19V5C3 3.9 3.9 3 5 3H6V1H8V3H16V1H18V3H19C20.11 3 21 3.9 21 5M5 7H19V5H5V7M19 19V9H5V19H19M15 13V11H17V13H15M11 13V11H13V13H11M7 15H9V17H7V15M15 17V15H17V17H15M11 17V15H13V17H11Z" fill="#596D48"/></svg>
                    <input type="date" id="reservation_date" name="reservation_date" required>
                </div>
                
                <div id="inner">
                    <label for="start_time">Start Time:</label>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>clock-in</title><path d="M2.21,0.79L0.79,2.21L4.8,6.21L3,8H8V3L6.21,4.8M12,8C8.14,8 5,11.13 5,15A7,7 0 0,0 12,22C15.86,22 19,18.87 19,15A7,7 0 0,0 12,8M12,10.15C14.67,10.15 16.85,12.32 16.85,15A4.85,4.85 0 0,1 12,19.85C9.32,19.85 7.15,17.68 7.15,15A4.85,4.85 0 0,1 12,10.15M11,12V15.69L14.19,17.53L14.94,16.23L12.5,14.82V12" fill="#596D48"/></svg>
                    <input type="time" id="start_time" name="start_time" required>
                </div>
                
                <div id="inner">
                    <label for="end_time">End Time:</label>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><title>clock-out</title><path d="M18,1L19.8,2.79L15.79,6.79L17.21,8.21L21.21,4.21L23,6V1M12,8C8.14,8 5,11.13 5,15A7,7 0 0,0 12,22C15.86,22 19,18.87 19,15A7,7 0 0,0 12,8M12,10.15C14.67,10.15 16.85,12.32 16.85,15A4.85,4.85 0 0,1 12,19.85C9.32,19.85 7.15,17.68 7.15,15A4.85,4.85 0 0,1 12,10.15M11,12V15.69L14.19,17.53L14.94,16.23L12.5,14.82V12" fill="#596D48"/></svg>
                    <input type="time" id="end_time" name="end_time" required>
                </div>
            </div>
            
            <!--<input type="hidden" name="customer_id" value="1"> -->

            <button class="frm-btn" type="submit">Submit Reservation</button>
        </form>
    </div>

    <?php include('footer.php'); ?>
</body>
</html>


