<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php

include '../includes/db.php';
include '../superadmin/log_activity.php';


if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    logActivity($_SESSION['user_id'], $_SESSION['username'], "Delete data of Course");
}
$course_id = $_GET['id'];

// Delete query
$sql = "DELETE FROM courses WHERE course_id='$course_id'";

if (mysqli_query($conn, $sql)) {
    $_SESSION['delcourse']="<div class='alert alert-success'>Deleted course successfully!</div>";
    echo "Course deleted successfully!";
} else {
    $_SESSION['delcourse']="<div class='alert alert-danger'>Error: " . mysqli_error($conn)."</div>";

}

header('Location: ../superadmin/courses.php');
?>
