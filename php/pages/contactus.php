<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/stock-trading-simulator/php/account/tradingdg13_connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: /stock-trading-simulator/php/auth/login.php");
    exit;
}

$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="/stock-trading-simulator/assets/css/contactus.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
</head>
<body>
<div class="sidebar">
    <div class="sidebar-item"><a href="/stock-trading-simulator/index.php"><i class="ri-global-line"></i></a></div>
    <div class="sidebar-item"><a href="/stock-trading-simulator/php/pages/stockpage.php"><i class="ri-bar-chart-line"></i></a></div>
    <div class="sidebar-item"><a href="/stock-trading-simulator/php/pages/wallet.php"><i class="ri-folder-line"></i></a></div>
    <div class="sidebar-item"><a href="/stock-trading-simulator/php/pages/portfolio.php"><i class="ri-arrow-right-line"></i></a></div>
    <div class="sidebar-item"><a href="/stock-trading-simulator/php/pages/settings.php"><i class="ri-settings-line"></i></a></div>
    <div class="sidebar-item"><a href="/stock-trading-simulator/php/pages/contactus.php"><i class="ri-question-line"></i></a></div>
</div>

<div class="navbar">
    <div class="user-info">
        <div class="user-details">
            <span class="user-name"><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></span>
            <span class="account-info">Username: <?php echo htmlspecialchars($username); ?></span>
        </div>
    </div>
    <div class="notifications">
        <div class="divider"></div>
        <i class="ri-notification-line"></i>
    </div>
    <div class="search-bar">
    </div>
    <div class="logout-button">
        <a href="/stock-trading-simulator/php/auth//stock-trading-simulator/php/auth/logout.php" class="logout-link"><i class="ri-logout-box-line"></i> Logout</a>
    </div>
</div>

    <div class="contact-container">
        <h1>Contact Us</h1>
        <p>Let us know what problems you are facing by contacting us:</p>

        <div class="contact-info">
            <h2>Any Enquiries, Reach Out To:</h2>
            <p>Email: nasdaq.trade@trade.com.sg</p>
            <p>Phone: 9409-8952</p>
            <p>Office: 20 Nanyang Avenue, 639798, Singapore</p>
        </div>
        <div class="thank-you-message" id="thank-you-message">
           <p>Thank you! Our team members will contact you shortly regarding your enquiry.</p> 
        </div>
        <div class="contact-form">
            <h2>Ask Us A Question</h2>
            <form method="POST" onsubmit="return visible()">
                <div class="form-row">
                    <input type="text" placeholder="First Name" name="first_name" required>
                    <input type="text" placeholder="Last Name" name="last_name" required>
                </div>
                <input type="email" placeholder="Email Address" name="email" required>
                <textarea name="message" placeholder="How can we help you?" rows="5" required></textarea>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
    <script>
        let message = document.getElementById("thank-you-message");
    
        function visible(){
            message.classList.add("visible-thank-you-message");
  
            setTimeout(() => {
                message.classList.remove("visible-thank-you-message");
        }, 5000);
        return false;
        }
    
    </script>
</body>
</html>
