<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php

include '../includes/db.php';

$course_id = $_GET['id'];

// Delete query
$sql = "DELETE FROM courses WHERE course_id='$course_id'";

if (mysqli_query($conn, $sql)) {
    echo "Course deleted successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}

header('Location: ../superadmin/courses.php');
?>
