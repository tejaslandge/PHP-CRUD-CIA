<?php
session_start();
include '../includes/db.php';
include '../superadmin/log_activity.php';


if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];
        $start_date = $_POST['start_date'];
        $due_date = $_POST['due_date'];
        $completion_date = $_POST['completion_date'];
        $task_status = $_POST['task_status'];
        $priority_level = $_POST['priority_level'];
        $remarks = $_POST['remarks'];

        // Update the task
        $sql = "UPDATE task_sheet SET task_name=?, task_description=?, start_date=?, due_date=?, completion_date=?, task_status=?, priority_level=?, remarks=? WHERE task_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssssi', $task_name, $task_description, $start_date, $due_date, $completion_date, $task_status, $priority_level, $remarks, $task_id);

        if ($stmt->execute()) {
            if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
                logActivity($_SESSION['user_id'], $_SESSION['username'], "Update Task: $task_name");
            }
            header("Location:../superadmin/task_sheet.php");
            exit;
        } else {
            echo "Error updating task.";
        }
    } else {
        $sql = "SELECT * FROM task_sheet WHERE task_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $task_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $task = $result->fetch_assoc();
        }
    }
}
?>
<!-- HTML form for editing the task -->
<form method="post">
    Task Name: <input type="text" name="task_name" value="<?php echo $task['task_name']; ?>"><br>
    Description: <input type="text" name="task_description" value="<?php echo $task['task_description']; ?>"><br>
    Start Date: <input type="date" name="start_date" value="<?php echo $task['start_date']; ?>"><br>
    Due Date: <input type="date" name="due_date" value="<?php echo $task['due_date']; ?>"><br>
    Completion Date: <input type="date" name="completion_date" value="<?php echo $task['completion_date']; ?>"><br>
    Status: <input type="text" name="task_status" value="<?php echo $task['task_status']; ?>"><br>
    Priority: <input type="text" name="priority_level" value="<?php echo $task['priority_level']; ?>"><br>
    Remarks: <input type="text" name="remarks" value="<?php echo $task['remarks']; ?>"><br>
    <input type="submit" value="Update Task">
</form>
