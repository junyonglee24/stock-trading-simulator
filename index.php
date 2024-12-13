<?php

include $_SERVER['DOCUMENT_ROOT'] . "/stock-trading-simulator/php/account/tradingdg13_connect.php";
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
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="/stock-trading-simulator/assets/css/dashboard-styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/stock-trading-simulator/assets/js/dashboard.js"></script>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-item"><a href="index.php"><i class="ri-global-line"></i></a></div>
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
    <div class="dashboard">
        <div class="top-panel">
            <div class="banner">
                <div class="indexes">.INX + 28.5 (+0.51%)</div>
                <div class="indexes">AMZN +173.92 (+1.48%)</div>
                <div class="indexes">NI225 +36.00</div>
            </div>
        </div>
        <div class="charts-container">
            <div class="charts-indiv-container">
                <div class="chart-title">
                    <h3>Indexes</h3>
                </div>
                <input type="hidden" id="nasdaq" name="nasdaq" value="NASDAQ">
                <div class="chart-box" id="index-chart">
                </div>
            </div>
            <div class="charts-indiv-container">
                <div class="chart-title">
                    <h3>Watchlist</h3>
                </div>
                <div class="watchlist">
                <div class="watchlist-item" id="watchlist-item-MSFT">
        <div class="stock-info">
            <div class="stock-symbol">
                <a href="/stock-trading-simulator/php/pages/stockpage.php?symbol=MSFT" target="_blank">MSFT</a>
            </div>
            <div class="stock-name">
                Microsoft Corp.
            </div>
            <div class="stock-chart">
                <canvas id="chart-MSFT" width="50" height="20"></canvas>
            </div>
        </div>
        <div class="stock-pricing">
            <div class="stock-price" id="price-NVDA">418.01</div>
            <div class="stock-change" id="change-NVDA">-1.07%</div>
            <div class="additional-info" id="additional-NVDA">417.60 -0.098% Pre</div>
        </div>
        </div>
                <div class="watchlist-item" id="watchlist-item-NVDA">
        <div class="stock-info">
        <div class="stock-symbol">
            <a href="/stock-trading-simulator/php/pages/stockpage.php?symbol=NVDA" target="_blank">NVDA</a>
        </div>
            <div class="stock-name">
                NVIDIA
            </div>
            <div class="stock-chart">
                <canvas id="chart-NVDA" width="50" height="20"></canvas>
            </div>
        </div>
        <div class="stock-pricing">
            <div class="stock-price" id="price-NVDA">145.26</div>
            <div class="stock-change" id="change-NVDA">-1.61%</div>
            <div class="additional-info" id="additional-NVDA">144.79 -0.32% Pre</div>
        </div>
    </div>
        <div class="watchlist-item" id="watchlist-item-APPL">
        <div class="stock-info">
            <div class="stock-symbol">
            <a href="/stock-trading-simulator/php/pages/stockpage.php?symbol=APPL" target="_blank">APPL</a>
            </div>
            <div class="stock-name">
                Apple Inc.
            </div>
            <div class="stock-chart">
                <canvas id="chart-APPL" width="50" height="20"></canvas>
            </div>
        </div>
        <div class="stock-pricing">
            <div class="stock-price" id="price-NVDA">224.23</div>
            <div class="stock-change" id="change-NVDA">-1.20%</div>
            <div class="additional-info" id="additional-NVDA">223.18 -0.47% Pre</div>
        </div>
    </div>
    <div class="watchlist-item" id="watchlist-item-GOOGL">
        <div class="stock-info">
            <div class="stock-symbol">
                <a href="/stock-trading-simulator/php/pages/stockpage.php?symbol=GOOGL" target="_blank">GOOGL</a>
            </div>
            <div class="stock-name">
                Alphabet Inc.
            </div>
            <div class="stock-chart">
                <canvas id="chart-APPL" width="50" height="20"></canvas>
            </div>
        </div>
        <div class="stock-pricing">
            <div class="stock-price" id="price-NVDA">181.97</div>
            <div class="stock-change" id="change-NVDA">+1.17%</div>
            <div class="additional-info" id="additional-NVDA">181.50 +0.26% Pre</div>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
        <div class="lower-panel">
            <div class="heat-map" id="heat-map">
                <div class="heat-map-title">
                    <h3>Heat Map</h3>
                </div>
                <div class="heat-map-charts">
                    <div class="heat-map-controls">
                        <select id="industry-select">
                            <option value="Popular">Popular</option>
                            <option value="Tech">Tech</option>
                            <option value="Finance">Finance</option>
                            <option value="Healthcare">Healthcare</option>
                        </select>
                        <div class="time-frame">
                            <button>D</button>
                            <button>W</button>
                            <button>M</button>
                            <button>Y</button>
                        </div>
                    </div>
                    <div class="heat-map-grid">
                        <div class="map-sector" style="width: 50%; height: 200px; background-color: #4caf50;">Information Technology</div>
                        <div class="map-sector" style="width: 25%; height: 150px; background-color: #f44336;">Financials</div>
                        <div class="map-sector" style="width: 25%; height: 150px; background-color: #2196f3;">Consumer Staples</div>
                        <div class="map-sector" style="width: 15%; height: 100px; background-color: #ffeb3b;">Healthcare</div>
                        <div class="map-sector" style="width: 35%; height: 150px; background-color: #8bc34a;">Energy</div>
                        <div class="map-sector" style="width: 20%; height: 100px; background-color: #e91e63;">Utilities</div>
                    </div>
                </div>
            </div>
            <div class="news-panel" id="top-news">
                <div class="news-panel-title">
                    <h3>Top News</h3>
                </div>
                <div class="news-content">
                <ul>
                    <li>Oil Prices Climb, Energy Stocks Rally - 5 min ago</li>
                    <li>Federal Reserve Hints at Rate Hike, Market Reacts - 15 min ago</li>
                    <li>Pharma Stock Dives Amid Regulatory Concerns - 1 hour ago</li>
                    <li>Gold Prices Dip as Dollar Strengthens - 3 hours ago</li>
                    <li>Auto Industry Strikes Affect Supply Chains, Shares Drop - 7 hours ago</li>
                    <li>Healthcare Stocks Surge on New Drug Approval - 9 hours ago</li>
                    <li>Renewable Energy Sector Gains Momentum, Stocks Rise - 14 hours ago</li>
                    <li>Luxury Retailer Posts Strong Sales, Shares Jump - 1 day ago</li>
                    <li>Banking Stocks Slide Amid Economic Slowdown Fears - 1 day ago</li>
                    <li>Travel Industry Rebounds, Airline Stocks Gain - 2 days ago</li>
                    <li>Global Chip Shortage Eases, Tech Stocks Respond - 30 min ago</li>
                    <li>Real Estate Market Cools, Property Stocks Slip - 4 hours ago</li>
                </ul>
                </div>
            </div>
           
        </div>
    </div>
</body>
</html>
