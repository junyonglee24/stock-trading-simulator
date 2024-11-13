<?php 
include 'tradingdg13_connect.php';

$userid = $_SESSION['username'];

//Virtual wallet
$sql = "SELECT balance FROM virtualwallet WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);

$stmt->execute();
$stmt->bind_result($balance);
if (!$stmt->fetch()) {
    $balance = 0;
}

$stmt->close();

$sql = "SELECT SUM(totalprice) AS totalStockValue FROM transactions WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$stmt->bind_result($totalStockValue);
$stmt->fetch();
$stmt->close();

// Default total stock value to zero if no stocks found
$totalStockValue = $totalStockValue ?? 0;

$accountValue = $balance + $totalStockValue;

$conn->close();
?>