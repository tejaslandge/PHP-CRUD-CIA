<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php

include '../includes/db.php';
include '../includes/header.php';
include 'log_activity.php';


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
        echo "New course added successfully!";
        header('Location:../superadmin/courses.php  ');
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
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Add Course</button>
    </form>
</div>

<?php
include '../includes/footer.php';
?>
