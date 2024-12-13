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

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Managed Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="/stock-trading-simulator/assets/css/stockpage.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
    <?php 
    include $_SERVER['DOCUMENT_ROOT'] . '/stock-trading-simulator/php/account/holdings_stats.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/stock-trading-simulator/php/account/retrieve_balance.php';
    // include $_SERVER['DOCUMENT_ROOT'] . '/stock-trading-simulator/php/account/retrieve_/stock-trading-simulator/php/account/transactions.php'
    ?>
    <script>
        var portfolio_data = <?php echo $portfolio_data; ?>;
    </script>
    <script src="/stock-trading-simulator/assets/js/chart_csv.js"></script>
    <script src="/stock-trading-simulator/assets/js/stock_api_csv.js"></script>
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
            <!-- <img src="profile-picture.jpg" alt="Profile Picture" class="user-icon"> -->
            <div class="user-details">
                <span class="user-name">
                    <?php 
                        echo htmlspecialchars(isset($firstName) ? $firstName : 'Jun Yong'); 
                        echo ' '; 
                        echo htmlspecialchars(isset($lastName) ? $lastName : 'Lee'); 
                    ?>
                </span>
                <span class="account-info">Username: 
                    <?php echo htmlspecialchars(isset($username) ? $username : 'jylee'); ?>
                </span>
            </div>
        </div>
        <div class="notifications">
            <div class="divider"></div>
            <i class="ri-notification-line"></i>
        </div>
        <div class="nav-bar-search-bar">
        </div>
        <div class="logout-button">
            <a href="/stock-trading-simulator/php/auth//stock-trading-simulator/php/auth/logout.php" class="logout-link"><i class="ri-logout-box-line"></i> Logout</a>
        </div>
    </div>
        <div class="top-panel">
                <div class="banner">
                    <div class="indexes">.INX + 28.5 (+0.51%)</div>
                    <div class="indexes">AMZN +173.92 (+1.48%)</div>
                    <div class="indexes">NI225 +36.00</div>
                </div>
        </div>
        <div id="portfolio">
            <div id="portfolio-title">
                <h1>Managed Portfolio</h1>
            </div>
            <div id="performance">
                <div id="performance-title">
                    <h2>Performance</h2>
                <div>
                <div id="rsi-chart">
                </div>
            </div>
            <div id="holdings">
                <div id="holdings-title">
                    <h2>Holdings</h1>
                </div>
                <div id="holdings-stocks">
                    <div class="holdings-container-header">
                        <table id="holdings-table-header">
                            <thead>
                                <tr>
                                    <td class="holdings-summmary">Total Value</td>
                                    <td class="holdings-summmary">Today's Change</td>
                                    <td class="holdings-summmary">Total Gain/Loss</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="holdings-total-value"><?php echo "$" . number_format($totalStockValue, 2, '.', ','); ?></td>
                                    <td class="today_change">$0.00 (0.00%)</td>
                                    <td id="holdings-total-gain-loss">$0.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="holdings-container">
                        <table id="holdings-table">
                            <thead>
                                <tr>
                                    <td>Symbol</td>
                                    <td>Description</td>
                                    <td>Current Price</td>
                                    <td>Today's Change</td>
                                    <td>Purchase Price</td>
                                    <td>QTY</td>
                                    <td>Total Value</td>
                                    <td>Total Gain / Loss</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($trades)): ?>
                                    <?php foreach ($trades as $trade): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($trade['identifier']); ?></td>
                                            <td id="stock-descriptions-<?= htmlspecialchars($trade['identifier']); ?>">Stock descriptions loading...</td>
                                            <td id="current-price-<?= htmlspecialchars($trade['identifier']); ?>">Current price loading...</td>
                                            <td id="today-change-<?= htmlspecialchars($trade['identifier']); ?>">Today's change loading...</td>
                                            <td><?= htmlspecialchars('$' . number_format(ceil((float)$trade['stockvalue'] / (float)$trade['quantity']))); ?></td>
                                            <td><?= htmlspecialchars($trade['quantity']); ?></td>
                                            <td><?= htmlspecialchars($trade['stockvalue']); ?></td>
                                            <td id="total-gain-loss-<?= htmlspecialchars($trade['identifier']); ?>">Total gain / loss loading...</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9">No holdings available</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>    
        <div>
</body>
</html>
