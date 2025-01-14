<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo "You must be logged in to add tasks.";
        exit();
    }

    $task_name = mysqli_real_escape_string($conn, $_POST['task_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $sql = "INSERT INTO tasks (user_id, task_name, category) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("iss", $_SESSION['user_id'], $task_name, $category);

    if ($stmt->execute()) {
        echo "Task added successfully!";
        header('Location: dashboard.php');
        exit();
    } else {
        echo "Something went wrong. Please try again.";
    }
}
?>
