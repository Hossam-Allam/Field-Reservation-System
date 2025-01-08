<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Form</title>
</head>

<body>
    <div class="main">
        <div class="left">
            <img class="bg" src="football-goal.webp" alt="background">
            <div class="odin">
            <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" class="odimg"><title>soccer</title><path d="M16.93 17.12L16.13 15.76L17.59 11.39L19 10.92L20 11.67C20 11.7 20 11.75 20 11.81C20 11.88 20.03 11.94 20.03 12C20.03 13.97 19.37 15.71 18.06 17.21L16.93 17.12M9.75 15L8.38 10.97L12 8.43L15.62 10.97L14.25 15H9.75M12 20.03C11.12 20.03 10.29 19.89 9.5 19.61L8.81 18.1L9.47 17H14.58L15.19 18.1L14.5 19.61C13.71 19.89 12.88 20.03 12 20.03M5.94 17.21C5.41 16.59 4.95 15.76 4.56 14.75C4.17 13.73 3.97 12.81 3.97 12C3.97 11.94 4 11.88 4 11.81C4 11.75 4 11.7 4 11.67L5 10.92L6.41 11.39L7.87 15.76L7.07 17.12L5.94 17.21M11 5.29V6.69L7 9.46L5.66 9.04L5.24 7.68C5.68 7 6.33 6.32 7.19 5.66S8.87 4.57 9.65 4.35L11 5.29M14.35 4.35C15.13 4.57 15.95 5 16.81 5.66C17.67 6.32 18.32 7 18.76 7.68L18.34 9.04L17 9.47L13 6.7V5.29L14.35 4.35M4.93 4.93C3 6.89 2 9.25 2 12S3 17.11 4.93 19.07 9.25 22 12 22 17.11 21 19.07 19.07 22 14.75 22 12 21 6.89 19.07 4.93 14.75 2 12 2 6.89 3 4.93 4.93Z" fill="white"/></svg>
                <p class="otext">OptiSport</p>
            </div>
        </div>

        <div class="right">
            <div class="top-right">
                <p class="hook one">
                    Welcome to Opti-Sport. Your all-in-one sports reservation system!<br>
                    To help you realize your deepest dreams.<br>
                    Sign up now to get started!
                </p>
                <p class="hook">You <i>know</i> you want to!</p>
            </div>

            <?php
                // Include the database connection
                require_once 'connect.php';

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $action = $_POST['action'] ?? '';

                    // Handle login
                    if ($action === 'login' && isset($_POST['email'], $_POST['password'])) {
                        $email = $_POST['email'];
                        $password = $_POST['password'];

                        if (!empty($email) && !empty($password)) {
                            $stmt = $conn->prepare("SELECT password FROM customers WHERE email = ?");
                            $stmt->bind_param("s", $email);
                            $stmt->execute();
                            $stmt->store_result();

                            if ($stmt->num_rows > 0) {
                                $stmt->bind_result($hashedPassword);
                                $stmt->fetch();

                                if (password_verify($password, $hashedPassword)) {
                                    echo '<script>alert("Login successful!");</script>';
                                } else {
                                    echo '<script>alert("Invalid credentials. Please try again.");</script>';
                                }
                            } else {
                                echo '<script>alert("No account found with this email.");</script>';
                            }

                            $stmt->close();
                        } else {
                            echo '<script>alert("Both fields are required.");</script>';
                        }
                    }

                    // Handle signup
                    else if ($action === 'signup') {
                        $firstName = $_POST['first_name'] ?? '';
                        $lastName = $_POST['last_name'] ?? '';
                        $email = $_POST['email'] ?? '';
                        $phone = $_POST['phone'] ?? '';
                        $password = $_POST['password'] ?? '';
                        $confirmPassword = $_POST['confirm'] ?? '';

                        $errors = [];
                        if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password)) {
                            $errors[] = "All fields are required.";
                        }
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $errors[] = "Invalid email format.";
                        }
                        if (!is_numeric($phone)) {
                            $errors[] = "Phone number must be numeric.";
                        }
                        if ($password !== $confirmPassword) {
                            $errors[] = "Passwords do not match.";
                        }

                        if (empty($errors)) {
                            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                            $stmt = $conn->prepare("INSERT INTO customers (name, surname, phone, email, password) VALUES (?, ?, ?, ?, ?)");
                            $stmt->bind_param("sssss", $firstName, $lastName, $phone, $email, $hashedPassword);

                            if ($stmt->execute()) {
                                echo '<p style="color: green;">Account created successfully!</p>';
                            } else {
                                echo '<p style="color: red;">Error: ' . $stmt->error . '</p>';
                            }

                            $stmt->close();
                        } else {
                            foreach ($errors as $error) {
                                echo '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
                            }
                        }
                    }
                }

            ?>

            <div id="form-container">
                <!-- Default Form (Sign-Up) -->
                <form id="signup-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <input type="hidden" name="action" value="signup">
                    <div class="shadow">
                        <h2>Let's do this</h2>
                        <div class="whole">
                            <div class="left-form">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" placeholder="EX: Hossam" name="first_name">

                                <label for="email">Email</label>
                                <input type="email" id="email" placeholder="EX: joe@gmail.com" name="email">

                                <label for="password">Password</label>
                                <input type="password" id="password" placeholder="Alphanumeric" name="password">
                            </div>

                            <div class="right-form">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" placeholder="EX: Allam" name="last_name">

                                <label for="phone">Phone Number</label>
                                <input type="number" id="phone" placeholder="0552423422" name="phone">

                                <label for="confirm">Confirm Password</label>
                                <input class="final" type="password" id="confirm" name="confirm">
                            </div>
                        </div>
                    </div>
                    <button type="submit">Create Account</button>
                    <p class="login">Already have an account? <span id="show-login">Log in</span></p>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('show-login').addEventListener('click', function () {
            const formContainer = document.getElementById('form-container');

            // Replace the current form with the login form
            formContainer.innerHTML = `
                <form id="login-form" action="" method="post">
                    <input type="hidden" name="action" value="login">
                    <div class="shadow">
                        <h2>Welcome Back</h2>
                        <div id="login-inputs">
                            <label for="login-email">Email</label>
                            <input type="email" id="login-email" placeholder="EX: joe@gmail.com" name="email">
                        
                            <label for="login-password">Password</label>
                            <input type="password" id="login-password" placeholder="Your Password" name="password">
                        </div>
                    </div>
                    <button type="submit">Log In</button>
                    <p class="login">Don't have an account? <span id="show-signup">Sign Up</span></p>
                </form>

            `;

            // Add event listener to the "Sign Up" button in the login form
            document.getElementById('show-signup').addEventListener('click', function () {
                location.reload(); // Reload to show the sign-up form again
            });
        });

        document.querySelector("form").addEventListener("submit", function(e) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm").value;
            const confirmField = document.getElementById("confirm");
            if (password !== confirmPassword) {
                e.preventDefault();
                confirmField.style.border = "2px solid red";
                alert("Passwords do not match!");
            }
        });
    </script>
</body>

</html>


