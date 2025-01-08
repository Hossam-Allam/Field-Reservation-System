<?php
// Database connection
$host = '127.0.0.1'; // Replace with your database host
$dbname = 'fieldreserva'; // Replace with your database name
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch fields from the database
$query = "SELECT * FROM fields WHERE availability = 1"; // Show only available fields
$stmt = $pdo->prepare($query);
$stmt->execute();
$fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Reservation</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file here -->
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
        .field-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .field-item {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .field-item h3 {
            margin: 0 0 10px;
        }
        .field-item p {
            margin: 5px 0;
            color: #555;
        }
        .reserve-btn {
            display: inline-block;
            padding: 10px 15px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .reserve-btn:hover {
            background: #0056b3;
        }
        .field-image {
            width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
            <!-- Display Logo -->
            <img src="images/logo.jpg" alt="Optiserve Logo" style="width: 80px; height: auto;"> <!-- Increased logo size -->
            <!-- Group Name -->
            <h1>Optiserve - Field Reservation</h1>
        </div>
        <p>Contact us at: <a href="mailto:info@optiserve.com" style="color: white;">info@optiserve.com</a></p>
    </header>

    <div class="container">
        <h2>Available Fields</h2>
        <?php if (count($fields) > 0): ?>
            <div class="field-list">
                <?php foreach ($fields as $field): ?>
                    <div class="field-item">
                        <!-- Display image for each field -->
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
