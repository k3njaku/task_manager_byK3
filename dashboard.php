<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'db_connect.php';

echo "<h1>Your Tasks</h1>";
$sql = "SELECT * FROM tasks WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

while ($task = $result->fetch_assoc()) {
    echo "<p>Task: " . htmlspecialchars($task['task_name']) . " | Category: " . htmlspecialchars($task['category']) . "</p>";
}
?>
