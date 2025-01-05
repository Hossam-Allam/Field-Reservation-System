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
                <img class="odimg" src="odin-lined.png" alt="odin">
                <p class="otext">OptiSport</p>
            </div>
        </div>

        <div class="right">
            <div class="top-right">
                <p class="hook one">
                    Welcome to Opti-Sport. Your all-in-one sports reservation system!<br> To help you realize
                    your deepest dreams.<br>
                    Sign up now to get started!
                </p>
                <p class="hook">You <i>know</i> you want to!</p>
            </div>

            <?php
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $firstName = $_POST['first_name'] ?? '';
                $lastName = $_POST['last_name'] ?? '';
                $email = $_POST['email'] ?? '';
                $phone = $_POST['phone'] ?? '';
                $password = $_POST['password'] ?? '';
                $confirmPassword = $_POST['confirm'] ?? '';

                
                if ($password !== $confirmPassword) {
                    echo '<p style="color: red;">Passwords do not match!</p>';
                } else {
                    
                    echo '<p style="color: green;">Form submitted successfully!</p>';
                }
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="shadow">
                    <h2>Let's do this</h2>
                    <div class="whole">
                        <div class="left-form">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" placeholder="EX: Hossam" name="first_name" required>

                            <label for="email">Email</label>
                            <input type="email" id="email" placeholder="EX: joe@gmail.com" name="email" required>

                            <label for="password">Password</label>
                            <input type="password" id="password" placeholder="Alphanumeric" name="password" required>
                        </div>

                        <div class="right-form">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" placeholder="EX: Allam" name="last_name" required>

                            <label for="phone">Phone Number</label>
                            <input type="number" id="phone" placeholder="0552423422" name="phone" required>

                            <label for="confirm">Confirm Password</label>
                            <input class="final" type="password" id="confirm" name="confirm" required>
                        </div>
                    </div>
                </div>
                <button type="submit">Create Account</button>
                <p class="login">Already have an account? <span>Log in</span></p>
            </form>
        </div>
    </div>

    <script>
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