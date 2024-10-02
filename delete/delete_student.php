<?php
session_start();
include '../includes/db.php';
include '../superadmin/log_activity.php';


if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // Delete query
    $sql = "DELETE FROM students WHERE student_id = $student_id";

    if (mysqli_query($conn, $sql)) {
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            logActivity($_SESSION['user_id'], $_SESSION['username'], "Delete student record");
        }
        header('Location: ../superadmin/students.php');
    } else {
        echo "Error deleting student: " . mysqli_error($conn);
    }
} else {
    echo "No student selected.";
}
?>
