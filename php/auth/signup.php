<?php
session_start();

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);

$successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['success_message']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📈 Create a New Account - NASDAQ-Trade</title>
    <link rel="stylesheet" href="/stock-trading-simulator/assets/css/signup.css">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="logo">
                <div class="logo-box">
                    <h1>📈 NASDAQ-Trade</h1>
                    <p>I E 4 7 2 7</p>
                </div>
            </div>
            <div class="description">
                <p>Join us and start trading today!</p>
                <div class="features">
                    <button>Stocks Analysis/Charting</button>
                    <button>Stocks News/Feeds</button>
                    <button>Virtual Wallet for Simulation</button>
                    <button>Trading history</button>
                </div>
            </div>
            <p class="footer-text">Project for IE4727</p>
        </div>
        
        <div class="right-side">
            <h2>Create a New Account</h2>

            <?php if (!empty($successMessage)) : ?>
            <p class="success-message"><?php echo htmlspecialchars($successMessage); ?></p>
            <?php endif; ?>

            <?php if (!empty($error)) : ?>
                <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form action="/stock-trading-simulator/php/auth/signup_account.php" method="post" class="signup-form" onsubmit="return validateForm()">
                <label for="name">First Name</label>
                <input type="text" id="firstname" name="firstname" placeholder="John" required>

                <label for="name">Last Name</label>
                <input type="text" id="lastname" name="lastname" placeholder="Doe" required>

                <label for="name">Username</label>
                <input type="text" id="username" name="username" placeholder="johndoe99" required>

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required>

                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <button type="button" class="show-password" onclick="togglePassword()">👁️</button>
                </div>

                <ul class="password-requirements">
                    <li>Must be at least 8 characters long</li>
                    <li>Must include at least one uppercase letter</li>
                    <li>Must include at least one lowercase letter</li>
                    <li>Must include at least one number</li>
                    <li>Must include at least one special character (e.g., @, $, !, %, *, ? &)</li>
                </ul>

                <label for="confirm-password">Confirm Password</label>
                <div class="password-container">
                    <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm Password" required>
                    <button type="button" class="show-password" onclick="toggleConfirmPassword()">👁️</button>
                </div>

                <button type="submit" class="create-account">Next Page</button>

                <div class="links">
                    <span>Already have an account? </span><a href="/stock-trading-simulator/php/auth/login.php">Log In</a>
                </div>
            </form>
        </div>
    </div>
    <script src="/stock-trading-simulator/assets/js/signup_validation.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
        }

        function toggleConfirmPassword() {
            const confirmPasswordField = document.getElementById("confirm-password");
            const type = confirmPasswordField.getAttribute("type") === "password" ? "text" : "password";
            confirmPasswordField.setAttribute("type", type);
        }
    </script>
</body>
</html>
