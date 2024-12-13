<?php 
include $_SERVER['DOCUMENT_ROOT'] . "/stock-trading-simulator/php/account/tradingdg13_connect.php";

$userid = $_SESSION['username'];

$sql = "SELECT * FROM transactions WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);

$stmt->execute();
$result = $stmt->get_result();

$wallet_trades = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['createdAt'] = date("n/j/Y", strtotime($row['createdAt']));
        $row['stockPrice'] = "$" . number_format($row['stockPrice'], 2, '.', ',');
        $row['totalprice'] = "$" . number_format($row['totalprice'], 2, '.', ',');
        $wallet_trades[] = $row;
    }
}

$stmt->close();
$conn->close();
?>
