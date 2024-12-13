<?php
include $_SERVER['DOCUMENT_ROOT'] . "/stock-trading-simulator/php/account/tradingdg13_connect.php";

$userid = $_SESSION['username'];

$sql = "SELECT asset_id, quantity, price FROM userportfolio WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$stmt->bind_result($asset_id, $quantity, $price);

$identifiers = [];
$quantities = [];
$stock_value = [];

while ($stmt->fetch()) {
    $identifiers[] = $asset_id;
    $quantities[] = $quantity;
    $stock_value[] = $price;

    $trades[] = [
        "identifier" => $asset_id,
        "quantity" => $quantity,
        "stockvalue" => $price
    ];
}

$data = [
    "identifier" => $identifiers,
    "quantities" => $quantities,
    "stockvalue" => $stock_value,
];

$stmt->close();
$conn->close();

$portfolio_data = json_encode($data);
?>