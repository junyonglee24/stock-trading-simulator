<?php
require '/stock-trading-simulator/php/account/tradingdg13_connect.php';

$sql = "SELECT id, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $plaintext_password = $row['password'];

        $hashed_password = password_hash($plaintext_password, PASSWORD_DEFAULT);

        $update_sql = "UPDATE users SET password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $hashed_password, $user_id);

        if ($update_stmt->execute()) {
            echo "Password updated for user ID: " . $user_id . "<br>";
        } else {
            echo "Error";
        }
    }
}
$conn->close();
?>