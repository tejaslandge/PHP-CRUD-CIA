<?php
session_start();
include '../includes/db.php';
include '../superadmin/log_activity.php';


if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    // Delete the task
    $sql = "DELETE FROM task_sheet WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $task_id);

    if ($stmt->execute()) {
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            logActivity($_SESSION['user_id'], $_SESSION['username'], "Delete Task");
        }
        header("Location:../superadmin/task_sheet.php");
        exit;
    } else {
        echo "Error deleting task.";
    }
}
?>
