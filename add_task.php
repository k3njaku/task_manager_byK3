<?php

session_start();
require 'db_connect.php';

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$sql = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn -> prepare($sql);
$stmt -> bind_param('s', $username);
$stmt -> execute();
$result = $stmt -> get_result();
if ($result -> num_rows > 0) {
    $user = $result -> fetch_assoc();

    // verifying password
    if (password_verify($password, $user['password'])) {
        // if password is correct store the user ID in the session.
        $_SESSION['user_id'] = $user['id'];
        header('Location: dashboard.php'); #Redirect to the dashboard#
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}