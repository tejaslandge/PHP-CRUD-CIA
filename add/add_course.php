<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php

// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error",0);

function error_display($errno, $errstr, $errfile, $errline){
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL,3,"../error/error_log.txt");
}
set_error_handler(callback: "error_display");


include '../includes/db.php';
include '../includes/header.php';
include '../superadmin/log_activity.php';




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = $_POST['course_name'];
    $course_description = $_POST['course_description'];
    $course_duration = $_POST['course_duration'];
    $course_fee = $_POST['course_fee'];
    $status = $_POST['status'];

    // Insert query
    $sql = "INSERT INTO courses (course_name, course_description, course_duration, course_fee, status) 
            VALUES ('$course_name', '$course_description', '$course_duration', '$course_fee', '$status')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['succmsg']= "<div class='alert alert-success'>New course added successfully!</div>";
        
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            logActivity($_SESSION['user_id'], $_SESSION['username'], "Add data of Course:$course_name");
        }
        // header('Location:../superadmin/courses.php  ');
        echo "<script>window.location.href = '../superadmin/courses.php';</script>";

    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>

<div class="container">
    <h2>Add New Course</h2>
    <form method="POST">
        <div class="form-group">
            <label for="course_name">Course Name</label>
            <input type="text" class="form-control" name="course_name" required>
        </div>
        <div class="form-group">
            <label for="course_description">Course Description</label>
            <textarea class="form-control" name="course_description" required></textarea>
        </div>
        <div class="form-group">
            <label for="course_duration">Course Duration</label>
            <input type="text" class="form-control" name="course_duration" required>
        </div>
        <div class="form-group">
            <label for="course_fee">Course Fee</label>
            <input type="number" class="form-control" name="course_fee" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Course</button>
    </form>
</div>

<?php
include '../includes/footer.php';
?>
