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
    <title id="title">Search & Trade</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="/stock-trading-simulator/assets/css/stockpage.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
    <script src="/stock-trading-simulator/assets/js/chart_csv.js"></script>
    <script src="/stock-trading-simulator/assets/js/stock_api_csv.js"></script> <!-- currently using csv format api data -->
    <script src="/stock-trading-simulator/assets/js/drop_down.js"></script>
    <script src="/stock-trading-simulator/assets/js/stockpage.js" defer></script>
    <script src="/stock-trading-simulator/assets/js/tradeform.js"></script>   
</head>
<body>
<?php
     include $_SERVER['DOCUMENT_ROOT'] . '/stock-trading-simulator/php/account/retrieve_balance.php';
?> 
    <div class="sidebar">
        <div class="sidebar-item"><a href="/stock-trading-simulator/index.php"><i class="ri-global-line"></i></a></div>
        <div class="sidebar-item"><a href="/stock-trading-simulator/php/pages/stockpage.php"><i class="ri-bar-chart-line"></i></a></div>
        <div class="sidebar-item"><a href="/stock-trading-simulator/php/pages/wallet.php"><i class="ri-folder-line"></i></a></div>
        <div class="sidebar-item"><a href="/stock-trading-simulator/php/pages/portfolio.php"><i class="ri-arrow-right-line"></i></a></div>
        <div class="sidebar-item"><a href="/stock-trading-simulator/php/pages/settings.php"><i class="ri-settings-line"></i></a></div>
        <div class="sidebar-item"><a href="/stock-trading-simulator/php/pages/contactus.php"><i class="ri-question-line"></i></a></div>
    </div>
    <div class="navbar">
        <div class="navbar-header">
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
    <div class="stock-content">
        <div class="trade-panel">
            <div id="trade-form-title">
                <p>Trade</p>
            </div>
            <div class="trade-header">
                <button id="buyButton" class="tab-button active" data-tab="buy" onclick="toggleTradeForm('buy')">Buy</button>
                <button id="sellButton" class="tab-button" data-tab="sell" onclick="toggleTradeForm('sell')">Sell</button>
            </div>
            <form id="tradeForm" action="/stock-trading-simulator/php/account/transactions.php" method="POST">
            <input type="hidden" id="buySellAction" name="buy_sell" value="Buy">
            <input type="hidden" id="identifier" name="identifier" value="AAPL">
                <div class="form-group">
                    <label>Order Type</label>
                    <select id="orderType" name="trade_type">
                        <option value="Market Open">Market Open</option>
                        <option value="Limit Order">Limit Order</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Price $</label>
                    <input type="text" id="price" name="price" value="Loading..." min="1" readonly/>
                </div>

                <div class="form-group">
                    <label>Quantity (Shares)</label>
                    <input type="number" id="quantity" name="quantity" value="0" min="1" />
                </div>

                <div class="form-group quantity-buttons">
                    <button type="button" class="quantity-btn" data-value="10">10</button>
                    <button type="button" class="quantity-btn" data-value="50">50</button>
                    <button type="button" class="quantity-btn" data-value="100">100</button>
                    <button type="button" class="quantity-btn" data-value="500">500</button>
                </div>

                <div class="form-group">
                    <label>Time-in-Force</label>
                    <select id="timeInForce">
                        <option value="day">Day</option>
                        <option value="gtc">GTC (Good 'Til Cancelled)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="toggle-switch">
                        <input type="checkbox" id="stop-price-checkbox">
                        <span class="slider"></span>
                    </label>
                    <span class="stop-price">Stop Price</span>
                    <input type="number" id="stopPrice" value="400.00" />
                </div>
                <div class="form-group">
                    <label>Est. Loss:</label>
                    <span id="estimatedLoss">$0.00</span>
                </div>
                <div class="transaction-info">
                    <p>Buying Power: <span id="buyingPower"><?php echo "$" . number_format($balance, 2); ?></span></p>
                    <p>Transaction Fees: <span id="transactionFees">$0.00</span></p>
                    <p>Estimated Total: <span id="estimatedTotal">$0.00</span></p>
                </div>
                
            </form>
            <button type="submit" class="trade-button" id="trade-button" onclick="openPopup()">Buy</button>
        </div>
        <div class="container">
            <div class="stock-header">
                <h1>Stock</h1>
            </div>
            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='confirmation-popup' id='confirmation-popupMessage'>
                <div class='confirmation-popup-content'>
                    <p>" . $_SESSION['message'] . "</p>
                    <button class='confirmation-close-button' onclick='closeConfirmationPopup()'>Close</button>
                </div>
            </div>";
                unset($_SESSION['message']);
            }
            ?>
            <div class="stock-search-bar">
                <input type="text" id="stockSymbolInput" placeholder="Look up Symbol / Company Name">
                <div id="suggestionsDropdown" class="suggestions-dropdown"></div>
            </div>
            <div class="stock-main">
                <div class="stock-info">
                    <table id="stock-table">                            
                        <tr>
                            <td id="stock-title" colspan="2">Loading...</td>
                            <td>Open</td>
                            <td id="open">Loading...</td>
                            <td>Shares Outstanding</td>
                            <td>7.43B</td>
                        </tr>
                        <tr>
                            <td rowspan="2" id="stockPrice">Loading...</td>
                            <td id="priceChange" class="price-change">Loading...</td>
                            <td>Low</td>
                            <td id="low">Loading...</td>
                            <td>Mkt Cap</td>
                            <td>3.02T</td>
                        </tr>
                        <tr>
                            <td id="percentageChange" class="price-change">Loading...</td>
                            <td>High</td>
                            <td id="high">Loading...</td>
                            <td>Div Yield</td>
                            <td>0.74%</td>
                        </tr>
                        <tr>
                            <td colspan="2"></td>
                            <td>Close</td>
                            <td id="close">Loading...</td>
                            <td><a href="#" class="view-all">View all</a></td>
                            <td></td>
                        </tr>
                        <tr>
                            <!-- <td>After hours:</td>
                            <td style="width:=80%"><span id="close">Loading...</span> <span>| <span id="lastUpdated">Loading...</span></td> -->
                            <td></td>
                            <td></td>
                            <td>Volume</td>
                            <td id="volume">Loading...</td>
                        </tr>
                    </table>
                </div>
            </div>
<!-- 
            <div class="stock-summary">
                <p>Open: <td id="open">Loading...</td> &nbsp; High: <span id="high">Loading...</span> &nbsp; Low: <span id="low">Loading...</span> &nbsp; Close: <span id="close">Loading...</span> &nbsp; Vol: <span id="volume">Loading...</span></p>
            </div> -->
            <div id="candlestick-chart">
            </div>
            <div id="rsi-chart">
            </div>         
        </div>
    </div> 
<div id="trade-form-alert">
    <div class="alert-content">
        <p id="alertMessage">This is a custom alert message.</p>
        <button onclick="closeAlert()">OK</button>
    </div>
</div>
<div class="popup" id="tradePopup">
    <div class="popup-content">
        <h2>Confirm transaction?</h2>
        <table>
            <tr>
                <td>Stock</td>
                <td id="popup-identifier"></td>
            </tr>
            <tr>
                <td>Action</td>
                <td id="popup-action"></td>
            </tr>
            <tr>
                <td>Trade Type</td>
                <td id="popup-trade-type"></td>
            </tr>
            <tr>
                <td>Price</td>
                <td id="popup-price"></td>
            </tr>
            <tr>
                <td>Quantity</td>
                <td id="popup-quantity"></td>
            </tr>
            <tr>
                <td>Transaction Fees (1%)</td>
                <td id="popup-transaction"></td>
            </tr>
            
        </table>
    </div>
    <h3 id="popup-totalprice">Total Price: </h3>
    <button onclick="confirmTrade()">Confirm</button>
    <button onclick="closePopup()">Cancel</button>
</div>
<script>
    function closeConfirmationPopup() {
        document.getElementById('confirmation-popupMessage').style.display = 'none';
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("quantity").addEventListener("focus", function() {
            this.select();
        });
    });
</script>
</body>
</html>
