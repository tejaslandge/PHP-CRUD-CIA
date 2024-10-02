<?php
include '../includes/db.php';

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $sql = "SELECT * FROM task_sheet WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $task_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
        // Display task details here
        echo "<h2>Task Details</h2>";
        echo "Task Name: " . $task['task_name'] . "<br>";
        echo "Description: " . $task['task_description'] . "<br>";
        echo "Start Date: " . $task['start_date'] . "<br>";
        echo "Due Date: " . $task['due_date'] . "<br>";
        echo "Completion Date: " . $task['completion_date'] . "<br>";
        echo "Status: " . $task['task_status'] . "<br>";
        echo "Priority: " . $task['priority_level'] . "<br>";
        echo "Remarks: " . $task['remarks'] . "<br>";
    } else {
        echo "Task not found.";
    }
} else {
    echo "Invalid request.";
}
?>
