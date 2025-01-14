<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
</head>

<body>
    <h1>Welcome to Task Manager</h1>
    <form action="add_task.php" method="POST">
        <label for="task_name">Task Name:</label>
        <input type="text" id="task_name" name="task_name" required>
        <br><br>

        <label for="category">Category:</label>
        <select id="category" name="category" required>
            <option value="Task Pending">Task Pending</option>
            <option value="Task Done">Task Done</option>
            <option value="Issues Need to be Fixed">Issues Need to be Fixed</option>
            <option value="Issues Resolved">Issues Resolved</option>
            <option value="Learning Activities">Learning Activities</option>
        </select>
        <br><br>

        <button type="submit">Add Task</button>
    </form>

</body>

</html>