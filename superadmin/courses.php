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


// Query to fetch data from the branches table
$sql = "SELECT * FROM courses ORDER BY course_id DESC";
$result = mysqli_query($conn, $sql);


// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error", 0);

function error_display($errno, $errstr, $errfile, $errline)
{
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL, 3, "../error/error_log.txt");
}
set_error_handler(callback: "error_display");
?>
    <link rel="icon" type="image" href="../assets/cia1.png" />

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<!-- Dashboard Container -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 d-none d-md-block col-lg-2 d-md-block bg-light fixed-top-10 ">
            <?php include '../includes/sidebar.php'; ?>
        </nav>
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <?php if (isset($_SESSION['addcourse'])): ?>
                <?php echo $_SESSION['addcourse']; ?>
                <?php unset($_SESSION['addcourse']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['editcourse'])): ?>
                <?php echo $_SESSION['editcourse']; ?>
                <?php unset($_SESSION['editcourse']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['delcourse'])): ?>
                <?php echo $_SESSION['delcourse']; ?>
                <?php unset($_SESSION['delcourse']); ?>
            <?php endif; ?>
            <?php
            // echo $_SESSION['succmsg'];
            ?>
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Courses</h1>
                <a href="../add/add_course.php" class="btn btn-primary mb-2">Add New Course</a>
            </div>

            <!-- Dashboard Stats -->
            
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Sr. No.</th>
                                    <th scope="col">Course Name</th>
                                    <th scope="col">Course Description</th>
                                    <th scope="col">Course Duration</th>
                                    <th scope="col">Course Fees</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Check if there are any results
                                if (mysqli_num_rows($result) > 0) {
                                    // Loop through each row and display the data
                                    $srno = 1;
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                        echo '<th scope="row">' . $srno . '</th>';
                                        echo '<td>' . $row['course_name'] . '</td>';
                                        echo '<td>' . $row['course_description'] . '</td>';
                                        echo '<td>' . $row['course_duration'] . '</td>';
                                        echo '<td>' . $row['course_fee'] . '</td>';
                                        echo '<td>';
                                        if ($row['status'] == 'active') {
                                            echo '<span class="badge bg-success">Active</span>';
                                        } else {
                                            echo '<span class="badge bg-danger">Inactive</span>';
                                        }
                                        echo '</td>';

                                        echo '<td>
                                            <a href="course_details.php?id=' . $row['course_id'] . '">
                                                <button class="btn btn-warning"><i class="fas fa-eye"></i></button>
                                            </a>
                                            <a href="../edit/edit_course.php?id=' . $row['course_id'] . '">
                                                <button class="btn btn-secondary"><i class="fas fa-edit"></i></button>
                                            </a>
                                            <a href="../delete/delete_course.php?id=' . $row['course_id'] . '" onclick="return confirm(\'Are you sure you want to delete this ' . $row['course_name'] . '?\');">
                                                <button class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                            </a>
                                        </td>';
                                        echo '</tr>';
                                        $srno++;
                                    }
                                } else {
                                    echo '<tr><td colspan="7">No branches found.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
