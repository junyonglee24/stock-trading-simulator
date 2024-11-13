<?php
include "tradingdg13_connect.php";
session_start();

if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📈 NASDAQ - Trade Login</title>
    <link rel="stylesheet" href="login.css">
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
                <p>What our platform does</p>
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
            <h2>Log In</h2>

            <?php
            if (isset($_SESSION['login_error'])) {
                echo '<p class="error-message">' . $_SESSION['login_error'] . '</p>';
                unset($_SESSION['login_error']); // Clear the error message after displaying it
            }
            ?>

            <form action="sessions.php" method="POST" class="login-form">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="you@example.com" required>

                <label for="password">Password</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <button type="button" class="show-password" onclick="togglePassword()">👁️</button>
                </div>

                <label class="remember-me">
                    <input type="checkbox" name="remember"> &nbsp; Remember this device
                </label>
                
                <button type="submit" class="sign-in">Sign In</button>

                <div class="links">
                    <a href="signup.php" class="create-account">Create an account</a>
                </div>
            </form>
        </div>
    </div>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);
        }
    </script>
</body>
</html>
