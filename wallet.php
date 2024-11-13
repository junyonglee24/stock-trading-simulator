<?php
include 'tradingdg13_connect.php';
session_start();

// $_SESSION['username'] = 'joshi37y';


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
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
    <title>Wallet</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="wallet.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <script type="text/javascript" src="dashboard.js"></script>
    <script type="text/javascript" src="stock_api_today.js"></script> -->
    <?php
    include 'holdings_stats.php';
    include 'retrieve_balance.php';
    include 'retrieve_transactions.php'
    ?>
    <script>
        var portfolio_data = <?php echo $portfolio_data; ?>;
    </script>
    <script src="stock_api_csv.js"></script>
</head>
<body>
    <div class="sidebar">
    <div class="sidebar-item"><a href="dashboard.php"><i class="ri-global-line"></i></a></div>
    <div class="sidebar-item"><a href="stockpage_view.php"><i class="ri-bar-chart-line"></i></a></div>
    <div class="sidebar-item"><a href="wallet.php"><i class="ri-folder-line"></i></a></div>
    <div class="sidebar-item"><a href="portfolio.php"><i class="ri-arrow-right-line"></i></a></div>
    <div class="sidebar-item"><a href="settings.php"><i class="ri-settings-line"></i></a></div>
    <div class="sidebar-item"><a href="contactus.php"><i class="ri-question-line"></i></a></div>
</div>

    <div class="navbar">
        <div class="user-info">
            <!-- <img src="profile-picture.jpg" alt="Profile Picture" class="user-icon"> -->
            <div class="user-details">
                <span class="user-name"><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></span>
                <span class="account-info">Username: <?php echo htmlspecialchars($userid); ?></span>
            </div>
        </div>
        <div class="notifications">
            <div class="divider"></div>
            <i class="ri-notification-line"></i>
        </div>
        <div class="search-bar">
        </div>
        <div class="logout-button">
        <a href="logout.php" class="logout-link"><i class="ri-logout-box-line"></i> Logout</a>
        </div>
    </div>
    <div class="top-panel">
                <div class="banner">
                    <div class="indexes">.INX + 28.5 (+0.51%)</div>
                    <div class="indexes">AMZN +173.92 (+1.48%)</div>
                    <div class="indexes">NI225 +36.00</div>
                </div>
        </div>
    <div class="main-content">
        <div class="wallet-container">
            <div class="wallet">
                <h1>Wallet</h1>
                <p class="subheader">Overview of your assets</p>
        
        <div class="account-overview">
            <div class="account-info">
                <h2>Account Value</h2>
                <p class="value"><?php echo "$" . number_format($accountValue, 2, '.', ','); ?></p>
            </div>
            <div class="account-info">
                <h2>Today's Change</h2>
                <p id="today_change">0.0 (0.00%)</p>
            </div>
            <div class="account-info">
                <h2>Cash Available</h2>
                <p class="value"><?php echo "$" . number_format($balance, 2, '.', ','); ?></p>
            </div>
            <button class="top-up" onclick="openPopup()">💳 Top Up</button>
        </div>
        
        <h2>Trade History</h2>
        <table class="trade-history">
        <thead>
            <tr>
                <th>Date</th>
                <th>Symbol</th>
                <th>Buy / Sell</th>
                <th>Trade Type</th>
                <th>Qty</th>
                <th>Purchase Price</th>
                <th>Total Cash Value</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($wallet_trades)): ?>
                <?php foreach ($wallet_trades as $trade): ?>
                    <tr>
                        <td><?= htmlspecialchars($trade['createdAt']); ?></td>
                        <td><?= htmlspecialchars($trade['identifier']); ?></td>
                        <td><?= htmlspecialchars($trade['buy_sell']); ?></td>
                        <td><?= htmlspecialchars($trade['trade_type']); ?></td>
                        <td><?= htmlspecialchars($trade['quantity']); ?></td>
                        <td><?= htmlspecialchars($trade['stockPrice']); ?></td>
                        <td><?= htmlspecialchars($trade['totalprice']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No trade history available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
<div class="popup" id="topupPopup">
    <div class="popup-content">
        <h1>➕💰</h1>
        <h2>Top Up Your Wallet</h2>
        <?php
                if (isset($_SESSION['message'])) {
                    echo '<p class="message">' . $_SESSION['message'] . '</p>';
                    unset($_SESSION['message']); // Clear the message after displaying it
                }
            ?>
        <form id="topupForm" action="topup.php" method="Post">
            <label for="topupAmount">Enter Amount: $</label>
            <input type="number" id="topupAmount" name="topupAmount" required min="1" placeholder="Enter amount">

            <div class="quick-amounts">
                <button type="button" onclick="setTopupAmount(10000)">$10,000</button>
                <button type="button" onclick="setTopupAmount(20000)">$20,000</button>
                <button type="button" onclick="setTopupAmount(50000)">$50,000</button>
            </div>
            <button type="submit">Submit</button>
        </form>
        <button class="close-btn" onclick="closeTopupPopup()">Close</button>
    </div>
</div>
</div>
<script>
    let popup = document.getElementById("topupPopup");

    function openPopup(){
        popup.classList.add("open-popup");
    }

    function closeTopupPopup(){
        popup.classList.remove("open-popup");    
    // Redirect to wallet.php after a delay (e.g., 1 second)
    setTimeout(() => {
        window.location.href = 'wallet.php';
    }, 1000);
    }

    function setTopupAmount(amount) {
    document.getElementById("topupAmount").value = amount;
}
</script>
</body>
</html>
