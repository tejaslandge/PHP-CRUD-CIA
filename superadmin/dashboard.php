<?php
session_start();


// Check if the user is logged in by verifying the session
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login page if not logged in
    exit;
}
?>

<?php
include '../includes/db.php';
include '../includes/header.php';





// SQL query to get the count of branches
$sqlbranch = "SELECT COUNT(*) AS total FROM branches";
$result = $conn->query($sqlbranch);

if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $branchcount = $row['total'];
} else {
    $branchcount = 0; // If no records found
}

// SQL query to get the count of Trainers
$sqltrainers = "SELECT COUNT(*) AS total FROM trainers";
$result = $conn->query($sqltrainers);

if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $trainerscount = $row['total'];
} else {
    $trainerscount = 0; // If no records found
}

// SQL query to get the count of Students
$sqlstudents = "SELECT COUNT(*) AS total FROM students";
$result = $conn->query($sqlstudents);

if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $studentscount = $row['total'];
} else {
    $studentscount = 0; // If no records found
}


// SQL query to get the count of Students
$sqlcourse = "SELECT COUNT(*) AS total FROM courses";
$result = $conn->query($sqlcourse);

if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $coursescount = $row['total'];
} else {
    $coursescount = 0; // If no records found
}


// SQL query to get the count of Students
$sqltasks_sheet = "SELECT COUNT(*) AS total FROM task_sheet";
$result = $conn->query($sqltasks_sheet);

if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $taskscount = $row['total'];
} else {
    $taskscount = 0; // If no records found
}


// SQL query to get the count of Students
$sqlfeedback = "SELECT COUNT(*) AS total FROM students";
$result = $conn->query($sqlfeedback);

if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $feedbackcount = $row['total'];
} else {
    $feedbackcount = 0; // If no records found
}

$conn->close();



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
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
            </div>

            <!-- Dashboard Stats -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-info text-white mb-4">
                        <div class="card-body">
                            <h5>Total Branches</h5>
                            <h2><?php echo $branchcount; ?></h2>
                        </div>
                        <div class="card-footer">
                            <a href="../superadmin/branches.php" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-secondary text-white mb-4">
                        <div class="card-body">
                            <h5>Total Trainers</h5>
                            <h2><?php echo $trainerscount; ?></h2> 
                        </div>
                        <div class="card-footer">
                            <a href="../superadmin/trainers.php" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <h5>Total Students</h5>
                            <h2><?php echo $studentscount; ?></h2>
                        </div>
                        <div class="card-footer">
                            <a href="../superadmin/students.php" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <h5>Total Courses</h5>
                            <h2><?php echo $coursescount; ?></h2>
                        </div>
                        <div class="card-footer">
                            <a href="../superadmin/courses.php" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">
                            <h5>Task Sheet</h5>
                            <h2><?php echo $taskscount; ?></h2>
                        </div>
                        <div class="card-footer">
                            <a href="../superadmin/task_sheet.php" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">
                            <h5>Feedbacks</h5>
                            <h2><?php echo $feedbackcount; ?></h2>
                        </div>
                        <div class="card-footer">
                            <a href="../superadmin/feedbacks.php" class="text-white">View Details <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>            
        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>