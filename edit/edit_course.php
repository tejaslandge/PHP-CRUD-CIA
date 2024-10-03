<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php
// Error Handling 
error_reporting(E_ALL);
ini_set("Display_error", 0);

function error_display($errno, $errstr, $errfile, $errline) {
    $message = "Error : $errno , Error Message : $errstr, Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL, 3, "../error/error_log.txt");
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
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            logActivity($_SESSION['user_id'], $_SESSION['username'], "Edited Course: $course_name");
        }
        $_SESSION['editcourse'] = "<div class='alert alert-success'>Updated Course successfully!</div>";

        echo "<script>window.location.href = '../superadmin/courses.php';</script>";
        exit;
    } else {
        $_SESSION['editcourse'] = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch existing course details
$sql = "SELECT * FROM courses WHERE course_id='$course_id'";
$result = mysqli_query($conn, $sql);
$course = mysqli_fetch_assoc($result);

?>

<!-- Sidebar and main content layout -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 d-none d-md-block col-lg-2 bg-light">
            <?php include '../includes/sidebar.php'; ?>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Edit Course</h2>
            </div>

            <!-- Course edit form grid -->
            <div class="container mt-4">
                <?php if (isset($_SESSION['editcourse'])): ?>
                    <?php echo $_SESSION['editcourse']; ?>
                    <?php unset($_SESSION['editcourse']); ?>
                <?php endif; ?>

                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="course_name" class="form-label">Course Name</label>
                            <input type="text" class="form-control" name="course_name" id="course_name"
                                value="<?php echo $course['course_name']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="course_duration" class="form-label">Course Duration</label>
                            <input type="text" class="form-control" name="course_duration" id="course_duration"
                                value="<?php echo $course['course_duration']; ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="course_fee" class="form-label">Course Fee</label>
                            <input type="number" class="form-control" name="course_fee" id="course_fee"
                                value="<?php echo $course['course_fee']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active" <?php echo ($course['status'] == 'active') ? 'selected' : ''; ?>>
                                    Active</option>
                                <option value="inactive"
                                    <?php echo ($course['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="course_description" class="form-label">Course Description</label>
                            <textarea class="form-control" name="course_description" id="course_description" rows="3"
                                required><?php echo $course['course_description']; ?></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Course</button>
                </form>
            </div>
        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
