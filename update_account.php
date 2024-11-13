<?php
session_start();
include 'tradingdg13_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $username = $_SESSION['username']; 

    $query = "UPDATE users SET firstName = ?, lastName = ?, email = ? WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $username);

    if ($stmt->execute()) {
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['email'] = $email;

        header("Location: settings.php?status=success");
        
    } else {
        header("Location: settings.php?status=error");
        
    }
    
    $stmt->close();
    exit();
}
$conn->close();
?>
