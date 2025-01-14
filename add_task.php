<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); // Start the session
require 'db_connect.php'; // Include the database connection file

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare the SQL query to find the user in the database
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the username exists in the database
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Fetch the user data

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct; store the user ID in the session
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php'); // Redirect to the dashboard
            exit();
        } else {
            // Password is incorrect; display an error message
            echo "Invalid password.";
        }
    } else {
        // Username not found; display an error message
        echo "User not found.";
    }
} else {
    echo "Invalid request.";
}
