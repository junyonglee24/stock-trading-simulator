<?php
session_start();
require 'tradingdg13_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql); // stmt to prevent SQL injection
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($user && md5($password) === $user['password']) {
            $_SESSION['username'] = $user['username'];  
            $_SESSION['email'] = $user['email'];
            $_SESSION['firstName'] = $user['firstName'];
            $_SESSION['lastName'] = $user['lastName'];

            header("Location: dashboard.php"); 
            exit;
        } else {
            $_SESSION['login_error'] = "Incorrect email or password.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Email has not been registered yet. Create an account with us!";
            header("Location: login.php");
            exit();
    }
}
$conn->close();
?>
