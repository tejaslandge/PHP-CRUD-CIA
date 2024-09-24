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
logActivity($_SESSION['user_id'], $_SESSION['username'], "Viewed Course Details");

$course_id = $_GET['id'];

// Fetch course details
$sql = "SELECT * FROM courses WHERE course_id='$course_id'";
$result = mysqli_query($conn, $sql);
$course = mysqli_fetch_assoc($result);

?>
 
<div class="container">
    <h2>Course Details</h2>
    <table class="table table-bordered">
        <tr>
            <th>Course Name</th>
            <td><?php echo $course['course_name']; ?></td>
        </tr>
        <tr>
            <th>Course Description</th>
            <td><?php echo $course['course_description']; ?></td>
        </tr>
        <tr>
            <th>Course Duration</th>
            <td><?php echo $course['course_duration']; ?></td>
        </tr>
        <tr>
            <th>Course Fees</th>
            <td><?php echo $course['course_fee']; ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                <?php if ($course['status'] == 'active') { ?>
                    <span class="badge bg-success">Active</span>
                <?php } elseif ($course['status'] == 'completed') { ?>
                    <span class="badge bg-warning">Completed</span>
                <?php } else { ?>
                    <span class="badge bg-danger">Cancelled</span>
                <?php } ?>
            </td>
        </tr>
    </table>
    <a href="../superadmin/courses.php" class="btn btn-danger">Back to Courses</a>
    <a href="../edit/edit_course.php?id=<?php echo $course_id; ?>">
        <button class="btn btn-secondary">Edit Course</button>
    </a>
</div>


<?php
include '../includes/footer.php';
?>