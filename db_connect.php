<?php
// Database connection details
$host = "localhost";        // Database host (usually localhost)
$db_name = "task_management_db"; // Your database name
$username = "root";         // Database username (default for XAMPP is root)
$password = "";             // Database password (default for XAMPP is empty)

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
