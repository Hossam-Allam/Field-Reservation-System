<?php
    session_start();
    require_once 'connect.php';



    $is_logged_in = isset($_SESSION['admin_email']);
    $staff_email = $is_logged_in ? htmlspecialchars($_SESSION['staff_email']) : null;

    

    try {
        // Fetch customer details
        $query = "SELECT * FROM staff WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $staff_email);
        $stmt->execute();
        $result = $stmt->get_result();
        $staff = $result->fetch_assoc();

        if (!$staff) {
            die("Worker not found.");
        }

        //form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updates = [];
            $params = [];
            $types = '';

            // Checking if 'name' has changed
            if (!empty($_POST['name']) && $_POST['name'] !== $staff['name']) {
                $updates[] = "name = ?";
                $params[] = htmlspecialchars($_POST['name']);
                $types .= 's';
            }

            // Checking if 'email' has changed
            if (!empty($_POST['email']) && $_POST['email'] !== $staff['email']) {
                $updates[] = "email = ?";
                $params[] = htmlspecialchars($_POST['email']);
                $types .= 's';
            }

            // Checking if 'phone' has changed
            if (!empty($_POST['phone']) && $_POST['phone'] !== $staff['phone']) {
                $updates[] = "phone = ?";
                $params[] = htmlspecialchars($_POST['phone']);
                $types .= 's';
            }

            // only run updates if sthg changes
            if (!empty($updates)) {
                $query = "UPDATE customers SET " . implode(", ", $updates) . " WHERE customer_ID = ?";
                $stmt = $conn->prepare($query);

                
                $params[] = $customer['staff_id'];
                $types .= 'i';  

                
                $stmt->bind_param($types, ...$params);

                if ($stmt->execute()) {
                    echo "<script>alert('Profile updated successfully!'); window.location.href = 'profile.php';</script>";
                } else {
                    echo "<script>alert('Error updating profile.');</script>";
                }
            } else {
                echo "<script>alert('No changes detected.');</script>";
            }
        }


        // Fetching reservations
        $query = "SELECT * FROM reservations r 
                JOIN fields f ON r.field_id = f.field_id 
                WHERE r.reservation_date >= CURDATE()
                ORDER BY reservation_date ASC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservations = $result->fetch_all(MYSQLI_ASSOC);

        //available fields
        $query = "SELECT * FROM fields
            WHERE availability = 1";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $available = $result->fetch_all(MYSQLI_ASSOC);

        // Unavailable fields
        $query = "SELECT * FROM fields WHERE availability = 0";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        $unavailable = $result->fetch_all(MYSQLI_ASSOC);

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    //making a field unavailable
    if (isset($_GET['make_unavailable'])) {
        $field_id = intval($_GET['make_unavailable']); 
    
        $query = "UPDATE fields SET availability = 0 WHERE field_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $field_id);
    
        if ($stmt->execute()) {
            // Refresh the page after updating
            echo "<script>window.location.href = window.location.pathname;</script>";
            exit;
        } else {
            echo "<script>alert('Error updating field availability.');</script>";
        }
    }

    //making it available
    if (isset($_GET['make_available'])) {
        $field_id = intval($_GET['make_available']);  // Sanitize input
    
        $query = "UPDATE fields SET availability = 1 WHERE field_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $field_id);
    
        if ($stmt->execute()) {
            echo "<script>window.location.href = window.location.pathname;</script>";
            exit;
        } else {
            echo "<script>alert('Error updating field availability.');</script>";
        }
    }
    
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Interface</title>
    <link rel="stylesheet" href="styles/admin.css">
    <link rel="stylesheet" href="styles/HFstyles.css">
</head>
<body>
    <?php include('hf/staffH.php'); ?>

    <div class="container">
        <div class="profile-info">
            <h1>Information</h1>
            <form action="" method="POST">
                
                <div class="group">
                    <label for="name"><strong>Name:</strong></label>
                    <input type="text" id="name" name="name" placeholder="<?php echo htmlspecialchars($staff['name']); ?>" >
                </div>
                

                <div class="group">
                    <label for="email"><strong>Email:</strong></label>
                    <input type="email" id="email" name="email" placeholder="<?php echo htmlspecialchars($staff['email']); ?>" >
                </div>

                <div class="group">
                    <label for="phone"><strong>Phone:</strong></label>
                    <input type="text" id="phone" name="phone" placeholder="<?php echo htmlspecialchars($staff['phone']); ?>" >
                </div>

                <button id="sub" type="submit" class="edit-btn">Save Changes</button>
            </form>
        </div>
            <div class="h2-grid">
                <h2>Upcoming Reservations</h2>
                <h2>Available Fields</h2>
                <h2 class="last">Unavailable</h2>
            </div>
            <div class="outer">
                <div class="in">
                
                    <?php if (!empty($reservations)): ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <div class="inner">
                                <p><strong>Field:</strong> <?php echo htmlspecialchars($reservation['type']); ?></p>
                                <p><strong>Date:</strong> <?php echo htmlspecialchars($reservation['reservation_date']); ?></p>
                                <p><strong>Time:</strong> <?php echo htmlspecialchars($reservation['start_hour']); ?> - <?php echo htmlspecialchars($reservation['end_hour']); ?></p>
                                <p><strong>Fees:</strong> <?php echo htmlspecialchars($reservation['total_fee']); ?> TL</p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No reservations found.</p>
                    <?php endif; ?>
                </div>

                <div class="in">
                    <?php if (!empty($available)): ?>
                        <?php foreach($available as $afield): ?>
                            <div class="inner">
                                <h3><?php echo htmlspecialchars($afield['type']); ?></h3>
                                <p>Field ID: <?php echo htmlspecialchars($afield['field_id']); ?></p>
                                <p>Fees: <?php echo htmlspecialchars($afield['fees']); ?> TL</p>
                                <p>Capacity: <?php echo htmlspecialchars($afield['capacity']); ?> persons</p>
                                <a href="?make_unavailable=<?php echo htmlspecialchars($afield['field_id']); ?>"  class="unreserve-btn">Make unavailable</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No Fields found.</p>
                    <?php endif; ?>
                </div>

                <div class="in">
                    <?php if (!empty($unavailable)): ?>
                        <?php foreach($unavailable as $ufield): ?>
                            <div class="inner">
                                <h3><?php echo htmlspecialchars($ufield['type']); ?></h3>
                                <p>Field ID: <?php echo htmlspecialchars($ufield['field_id']); ?></p>
                                <p>Fees: <?php echo htmlspecialchars($ufield['fees']); ?> TL</p>
                                <p>Capacity: <?php echo htmlspecialchars($ufield['capacity']); ?> persons</p>
                                <a href="?make_available=<?php echo htmlspecialchars($ufield['field_id']); ?>" class="reserve-btn">Make Available</a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No unavailable fields found.</p>
                    <?php endif; ?>
                </div>
            </div>

    </div>
    
    <?php include('hf/footer.php'); ?>
</body>
</html>