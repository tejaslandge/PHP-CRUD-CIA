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

$course_id = $_GET['id'];

// Fetch course details
$sql = "SELECT * FROM courses WHERE course_id='$course_id'";
$result = mysqli_query($conn, $sql);
$course = mysqli_fetch_assoc($result);


// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error",0);

function error_display($errno, $errstr, $errfile, $errline){
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL,3,"../error/error_log.txt");
}
set_error_handler(callback: "error_display");
?>
 


<!-- Dashboard Container -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 d-none d-md-block col-lg-2 d-md-block bg-light fixed-top-10 ">
            <?php include '../includes/sidebar.php'; ?>
        </nav>
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
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
                <?php } else { ?>
                    <span class="badge bg-danger">Inactive</span>
                <?php } ?>
            </td>
        </tr>
    </table>
    <a href="../edit/edit_course.php?id=<?php echo $course_id; ?>">
        <button class="btn btn-primary">Edit Course</button>
    </a>
    <a href="../superadmin/courses.php" class="btn btn-secondary mx-3">Back</a>
</div>
        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>