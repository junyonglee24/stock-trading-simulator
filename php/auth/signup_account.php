<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/stock-trading-simulator/php/account/tradingdg13_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $password = md5($password);

    $checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username or email already taken. Please login or choose a different one.";
        header("Location: /stock-trading-simulator/php/auth/signup.php");
        exit();
    } else {
        $stmt->close();

        $conn->begin_transaction();

        try {
            $insertQuery = "INSERT INTO users (firstName, lastName, username, email, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sssss", $firstname, $lastname, $username, $email, $password);
            $stmt->execute();

            $balance = 500000; // every new account creation has $500,000 cash balance
            $walletQuery = "INSERT INTO virtualwallet (username, balance) VALUES (?, ?)";
            $stmt = $conn->prepare($walletQuery);
            $stmt->bind_param("si", $username, $balance);
            $stmt->execute();

            $conn->commit();

            $_SESSION['success_message'] = "Account creation successful!";
            header("refresh:2;url=/stock-trading-simulator/php/auth/signup_profile.php"); 
            exit();

        } catch (Exception $e) {
            $conn->rollback();  
            $_SESSION['error'] = "An error occurred during signup. Please try again.";
            header("Location: /stock-trading-simulator/php/auth/signup.php");
            exit();
        }
    }
}
$conn->close();
?>
