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




$course_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = $_POST['course_name'];
    $course_description = $_POST['course_description'];
    $course_duration = $_POST['course_duration'];
    $course_fee = $_POST['course_fee'];
    $status = $_POST['status'];



    // Update query
    $sql = "UPDATE courses SET course_name='$course_name', course_description='$course_description', 
            course_duration='$course_duration', course_fee='$course_fee', status='$status' 
            WHERE course_id='$course_id'";

    if (mysqli_query($conn, $sql)) {
        echo "Course updated successfully!";
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            logActivity($_SESSION['user_id'], $_SESSION['username'], "Edit data of Course : $course_name");
        }
        // header('Location:../superadmin/courses.php');
        echo "<script>window.location.href = '../superadmin/courses.php';</script>";

        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch existing course details
$sql = "SELECT * FROM courses WHERE course_id='$course_id'";
$result = mysqli_query($conn, $sql);
$course = mysqli_fetch_assoc($result);

?>

<div class="container">
    <h2>Edit Course</h2>
    <form method="POST">
        <div class="form-group">
            <label for="course_name">Course Name</label>
            <input type="text" class="form-control" name="course_name" value="<?php echo $course['course_name']; ?>"
                required>
        </div>
        <div class="form-group">
            <label for="course_description">Course Description</label>
            <textarea class="form-control" name="course_description"
                required><?php echo $course['course_description']; ?></textarea>
        </div>
        <div class="form-group">
            <label for="course_duration">Course Duration</label>
            <input type="text" class="form-control" name="course_duration"
                value="<?php echo $course['course_duration']; ?>" required>
        </div>
        <div class="form-group">
            <label for="course_fee">Course Fee</label>
            <input type="number" class="form-control" name="course_fee" value="<?php echo $course['course_fee']; ?>"
                required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status" required>
                <option value="active" <?php echo ($course['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo ($course['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive
                </option>
                
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Course</button>
    </form>
</div>

<?php
include '../includes/footer.php';
?>