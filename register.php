<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confim_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    if (empty($username) || empty($email) || empty($password) || empty($confim_password)) {
        echo "All fields are required.";
    } elseif ($password !== $confirm_password) {
        echo "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql_check);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "User already exists.";
        } else {
            $sql = "INSERT INTO users (username, email, password) VALUES (? , ? , ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                echo "Registration successful! You can now login.";
                header('Location: index.php');
                exit();
            } else {
                echo "Something went wrong, Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
</head>

<body>
    <h1>Register</h1>
    <!-- 
    Step 9: Registration form
    - A simple HTML form for collecting the user's registration details.
    - The form uses the POST method to send data to the server securely.
    -->
    <form action="register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required><br>

        <button type="submit">Register</button>
    </form>
</body>

</html>