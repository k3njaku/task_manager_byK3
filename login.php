<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieving and sanitizing form inputs
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Prepare the SQL query
    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    // Bind the username parameter and execute the query
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Retrieve the result of the query
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user data as an associative array
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            header('Location: dashboard.php'); // Redirect to the dashboard
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>

    <!-- Login Form -->
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a>.</p>
</body>

</html>