<?php
// Enable error reporting to display any issues during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start a session to check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header('Location: login.php');
    exit();
}

// Include database connection file
require 'db_connect.php';

/* 
Handle POST requests for:
1. Updating a task's category when the checkmark button is clicked.
2. Deleting a task when the bin button is clicked.
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle category update
    if (isset($_POST['task_id'], $_POST['current_category'], $_POST['update_category'])) {
        $task_id = intval($_POST['task_id']); // Convert task ID to integer
        $current_category = $_POST['current_category']; // Get the current category
        $new_category = ''; // Initialize the new category

        // Determine the new category based on the current category
        if ($current_category === 'Task Pending') {
            $new_category = 'Task Done';
        } elseif ($current_category === 'Issues Need to be Fixed') {
            $new_category = 'Issues Resolved';
        }

        // If a new category is determined, update the task in the database
        if ($new_category !== '') {
            $sql = "UPDATE tasks SET category = ? WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sii", $new_category, $task_id, $_SESSION['user_id']); // Bind parameters
                $stmt->execute(); // Execute the query
            }
        }
    }

    // Handle task deletion
    if (isset($_POST['task_id'], $_POST['delete_task'])) {
        $task_id = intval($_POST['task_id']); // Convert task ID to integer

        // Delete the task from the database
        $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ii", $task_id, $_SESSION['user_id']); // Bind parameters
            $stmt->execute(); // Execute the query
        }
    }
}

// Fetch all tasks for the logged-in user
$sql = "SELECT * FROM tasks WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']); // Bind the user ID to the query
$stmt->execute(); // Execute the query
$result = $stmt->get_result(); // Get the result set
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <style>
        .task {
            margin-bottom: 10px;
        }

        .task button {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <h1>Your Tasks</h1>

    <!-- Loop through all tasks and display them -->
    <?php while ($task = $result->fetch_assoc()): ?>
        <div class="task">
            <!-- Display task name and category -->
            <span>
                Task: <?= htmlspecialchars($task['task_name']) ?> |
                Category: <?= htmlspecialchars($task['category']) ?>
            </span>

            <!-- Checkmark button for category updates -->
            <?php if ($task['category'] !== 'Learning Activities'): ?>
                <form action="dashboard.php" method="POST" style="display: inline;">
                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                    <input type="hidden" name="current_category" value="<?= $task['category'] ?>">
                    <input type="hidden" name="update_category" value="1">
                    <button type="submit">✔</button>
                </form>
            <?php endif; ?>

            <!-- Bin button for deleting tasks -->
            <form action="dashboard.php" method="POST" style="display: inline;">
                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                <input type="hidden" name="delete_task" value="1">
                <button type="submit">🗑</button>
            </form>
        </div>
    <?php endwhile; ?>
</body>

</html>
