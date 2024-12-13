<?php 
session_start();

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
    <title>Account Settings</title>
    <link rel="stylesheet" href="/stock-trading-simulator/assets/css/settings.css">
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

<div class="settings-container">
    <h1>Account Settings</h1>
    <p>View and manage account details such as name, email address, contact information, etc.</p><br>

    <div class="settings-section">
        <h2>Personal Information</h2>
        <form action="/stock-trading-simulator/php/account/update_account.php" method="POST" onsubmit="return validateForm()">
            <div class="section-content">
                <div>
                    <p>First Name</p>
                    <input type="text" id=firstname class=textbox name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" disabled>
                </div>
                <div>
                    <p>Last Name</p>
                    <input type="text" id=lastname class=textbox name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" disabled>
                </div>
                <div>
                    <p>Email Address</p>
                    <input type="email" id=email class=textbox name="email" value="<?php echo htmlspecialchars($email); ?>" disabled>
                </div>
            </div>
            <button type="button" class="edit-button" onclick="enableEdit()">Edit</button>
            <button type="submit" class="submit-button" style="display: none;">Submit</button>
        </form>
    </div>
</div>
<script src="/stock-trading-simulator/assets/js/settings.js"></script>
</body>
</html>
