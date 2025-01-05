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
                    Welcome to Opti-Sport. Your all-in-one sports reservation system!<br>
                    To help you realize your deepest dreams.<br>
                    Sign up now to get started!
                </p>
                <p class="hook">You <i>know</i> you want to!</p>
            </div>

            <div id="form-container">
                <!-- Default Form (Sign-Up) -->
                <form id="signup-form" action="" method="post">
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
                    <div class="shadow">
                        <h2>Welcome Back</h2>
                        <div id="login-inputs">
                            <label for="email">Email</label>
                            <input type="email" id="email" placeholder="EX: joe@gmail.com" name="email">
                        
                            <label for="password">Password</label>
                            <input type="password" id="password" placeholder="Your Password" name="password">
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


