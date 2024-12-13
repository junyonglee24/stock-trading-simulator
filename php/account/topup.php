<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/stock-trading-simulator/php/account/tradingdg13_connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: /stock-trading-simulator/php/auth/login.php");
    exit;
}

$userid = $_SESSION['username'];
$topupAmount = isset($_POST['topupAmount']) ? (int)$_POST['topupAmount'] : 0;

if ($topupAmount > 0) {
    $sql = "UPDATE virtualwallet SET balance = balance + ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $topupAmount, $userid);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Top-up successful: $" . number_format($topupAmount);
    } else {
        $_SESSION['message'] = "Failed to update balance";
    }
    header("Location: /stock-trading-simulator/php/pages/wallet.php");

    $stmt->close();
} 
$conn->close();
