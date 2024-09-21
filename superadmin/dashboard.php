<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php
include '../includes/db.php';
include '../includes/header.php';
?>
<!-- count all data From db -->
<?php
include '../includes/db.php';

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

            <!-- Additional content -->
            <div class="row">
                <div class="col-md-12">
                    <h2>Recent Activity</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Activity</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Student John Doe enrolled in "Web Development"</td>
                                <td>2024-09-15</td>
                                <td><span class="badge bg-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Course "Python for Beginners" updated</td>
                                <td>2024-09-14</td>
                                <td><span class="badge bg-warning">In Progress</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Fee submission deadline for "Data Science" extended</td>
                                <td>2024-09-13</td>
                                <td><span class="badge bg-danger">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>