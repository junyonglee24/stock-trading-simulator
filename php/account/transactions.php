<?php
include $_SERVER['DOCUMENT_ROOT'] . "/stock-trading-simulator/php/account/tradingdg13_connect.php";
session_start();

if (isset($_SESSION['username'])) {
    $userid = $_SESSION['username'];
} else {
    $_SESSION['message'] = "User not logged in.";
    header("Location: /stock-trading-simulator/php/auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $identifier = htmlspecialchars($_POST['identifier']);
    $buy_sell = htmlspecialchars($_POST['buy_sell']);
    $trade_type = htmlspecialchars($_POST['trade_type']);
    $quantity = (int) $_POST['quantity'];
    $price = str_replace('$', '', $_POST['price']);
    $stockprice = (float) $price;
    $totalprice = $stockprice * $quantity;
    $transactionfee = $totalprice * 0.001;
    $totalprice = $totalprice + $transactionfee;
    $createdAt = date("Y-m-d H:i:s");

    if ($buy_sell === "Buy") {
        $balanceQuery = "SELECT balance FROM virtualwallet WHERE username = ?";
        $balanceStmt = $conn->prepare($balanceQuery);
        $balanceStmt->bind_param("s", $userid);
        $balanceStmt->execute();
        $balanceStmt->bind_result($balance);
        $balanceStmt->fetch();
        $balanceStmt->close();

        if ($balance < $totalprice) {
            $_SESSION['message'] = "Insufficient balance for this transaction.";
            header("Location: /stock-trading-simulator/php/pages/stockpage.php");
            exit();
        }

        $newBalance = $balance - $totalprice;
        $updateBalanceQuery = "UPDATE virtualwallet SET balance = ? WHERE username = ?";
        $updateBalanceStmt = $conn->prepare($updateBalanceQuery);
        $updateBalanceStmt->bind_param("ds", $newBalance, $userid);

        if (!$updateBalanceStmt->execute()) {
            $_SESSION['message'] = "Error updating balance: " . $updateBalanceStmt->error;
            $updateBalanceStmt->close();
            header("Location: /stock-trading-simulator/php/pages/stockpage.php");
            exit();
        }
        $updateBalanceStmt->close();

        $portfolioQuery = "SELECT quantity, price FROM userportfolio WHERE username = ? AND asset_id = ?";
    $portfolioStmt = $conn->prepare($portfolioQuery);
    $portfolioStmt->bind_param("ss", $userid, $identifier);
    $portfolioStmt->execute();
    $portfolioStmt->bind_result($portfolioQuantity, $portfolioTotalPrice);
    $portfolioStmt->fetch();
    $portfolioStmt->close();

    $newQuantity = $portfolioQuantity + $quantity;
    $newTotalPrice = $portfolioTotalPrice + $totalprice -$transactionfee;
    $stocktotalprice = $totalprice - $transactionfee;

    if ($portfolioQuantity !== null) {
        $updatePortfolioQuery = "UPDATE userportfolio SET quantity = ?, price = ? WHERE username = ? AND asset_id= ?";
        $updatePortfolioStmt = $conn->prepare($updatePortfolioQuery);
        $updatePortfolioStmt->bind_param("idss", $newQuantity, $newTotalPrice, $userid, $identifier);
        $updatePortfolioStmt->execute();
        $updatePortfolioStmt->close();
    } else {
        $insertPortfolioQuery = "INSERT INTO userportfolio (username, asset_id, quantity, price) VALUES (?, ?, ?, ?)";
        $insertPortfolioStmt = $conn->prepare($insertPortfolioQuery);
        $insertPortfolioStmt->bind_param("ssid", $userid, $identifier, $quantity, $stocktotalprice);
        $insertPortfolioStmt->execute();
        $insertPortfolioStmt->close();
    }
}
    } if ($buy_sell === "Sell") {

        $portfolioQuery = "SELECT quantity, price FROM userportfolio WHERE username = ? AND asset_id= ?";
        $portfolioStmt = $conn->prepare($portfolioQuery);
        $portfolioStmt->bind_param("ss", $userid, $identifier);
        $portfolioStmt->execute();
        $portfolioStmt->bind_result($portfolioQuantity, $portfolioTotalPrice);
        $portfolioStmt->fetch();
        $portfolioStmt->close();

        if (is_null($portfolioQuantity)) {
            $_SESSION['message'] = "Stock not found in your portfolio.";
            header("Location: /stock-trading-simulator/php/pages/stockpage.php");
            exit();
        }        

        if ($portfolioQuantity < $quantity) {
            $_SESSION['message'] = "Insufficient stock quantity for this transaction.";
            header("Location: /stock-trading-simulator/php/pages/stockpage.php");
            exit();
        }

        $newPortfolioQuantity = $portfolioQuantity - $quantity;
        $newPortfolioTotalPrice = $portfolioTotalPrice - $totalprice + $transactionfee;

        if ($newPortfolioQuantity > 0) {
            $updatePortfolioQuery = "UPDATE userportfolio SET quantity = ?, price = ? WHERE username = ? AND asset_id = ?";
            $updatePortfolioStmt = $conn->prepare($updatePortfolioQuery);
            $updatePortfolioStmt->bind_param("idss", $newPortfolioQuantity, $newPortfolioTotalPrice, $userid, $identifier);
            $updatePortfolioStmt->execute();
            $updatePortfolioStmt->close();
        } else {

            $deletePortfolioQuery = "DELETE FROM userportfolio WHERE username = ? AND asset_id = ?";
            $deletePortfolioStmt = $conn->prepare($deletePortfolioQuery);
            $deletePortfolioStmt->bind_param("ss", $userid, $identifier);
            $deletePortfolioStmt->execute();
            $deletePortfolioStmt->close();
        }

        $balanceQuery = "SELECT balance FROM virtualwallet WHERE username = ?";
        $balanceStmt = $conn->prepare($balanceQuery);
        $balanceStmt->bind_param("s", $userid);
        $balanceStmt->execute();
        $balanceStmt->bind_result($balance);
        $balanceStmt->fetch();
        $balanceStmt->close();

        $newBalance = $balance + $totalprice;
        $updateBalanceQuery = "UPDATE virtualwallet SET balance = ? WHERE username = ?";
        $updateBalanceStmt = $conn->prepare($updateBalanceQuery);
        $updateBalanceStmt->bind_param("ds", $newBalance, $userid);

        if (!$updateBalanceStmt->execute()) {
            $_SESSION['message'] = "Error updating balance: " . $updateBalanceStmt->error;
            $updateBalanceStmt->close();
            header("Location: /stock-trading-simulator/php/pages/stockpage.php");
            exit();
        }
        $updateBalanceStmt->close();
    }

    $sql = "INSERT INTO transactions (username, identifier, buy_sell, trade_type, quantity, stockPrice, totalprice, createdAt) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $_SESSION['message'] = "Error preparing statement: " . $conn->error;
        header("Location: /stock-trading-simulator/php/pages/stockpage.php");
        exit();
    }

    $stmt->bind_param("ssssidds", $userid, $identifier, $buy_sell, $trade_type, $quantity, $stockprice, $totalprice, $createdAt);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Trade submittted successfully.";
    } else {
        $_SESSION['message'] = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: /stock-trading-simulator/php/pages/stockpage.php");
    exit();
?>
