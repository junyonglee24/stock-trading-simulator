<?php
session_start();
include "tradingdg13_connect.php"; // Include database connection file

// Check if form data was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    // Retrieve form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password securely
    $password = md5($password);

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username or email already taken. Please login or choose a different one.";
        header("Location: signup.php");
        exit();
    } else {
        // Close the previous statement
        $stmt->close();

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Insert user data into users table
            $insertQuery = "INSERT INTO users (firstName, lastName, username, email, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sssss", $firstname, $lastname, $username, $email, $password);
            $stmt->execute();

            // Insert initial balance into virtualwallet table
            $balance = 500000;
            $walletQuery = "INSERT INTO virtualwallet (username, balance) VALUES (?, ?)";
            $stmt = $conn->prepare($walletQuery);
            $stmt->bind_param("si", $username, $balance);
            $stmt->execute();

            // Commit transaction
            $conn->commit();

            // Success message and redirect
            $_SESSION['success_message'] = "Account creation successful!";
            header("refresh:2;url=signup_profile.html"); // Redirect to profile page after 2 seconds
            exit();

        } catch (Exception $e) {
            // Rollback transaction if an error occurs
            $conn->rollback();
            $_SESSION['error'] = "An error occurred during signup. Please try again.";
            header("Location: signup.php");
            exit();
        }
    }
}
$conn->close();
?>
