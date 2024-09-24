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

logActivity($_SESSION['user_id'], $_SESSION['username'], "Viewed Courses Table");

// Query to fetch data from the branches table
$sql = "SELECT * FROM courses";
$result = mysqli_query($conn, $sql);
?>
    <link rel="icon" type="image" href="../assets/cia1.png" />

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
                                        echo '<td>' . $row['course_name']. '</td>';
                                        echo '<td>' . $row['course_description']. '</td>';
                                        echo '<td>' . $row['course_duration'] . '</td>';
                                        echo '<td>' . $row['course_fee'] . '</td>';
                                        echo '<td>';
                                        if ($row['status'] == 'active') {
                                            echo '<span class="badge bg-success">Active</span>';
                                        } elseif( $row['status'] == 'completed') {
                                            echo '<span class="badge bg-warning">Completed</span>';
                                        }
                                         else{
                                            echo '<span class="badge bg-danger">Cancelled</span>';
                                        }
                                        echo '</td>';

                                        echo '<td>
                                        <a href="course_details.php?id=' . $row['course_id'] . '"><button class="btn btn-warning fas fa-eye"></button></a>
                                         <a href="../edit/edit_course.php?id=' . $row['course_id'] . '">
                                                <button class="btn btn-secondary fas fa-edit"></button>
                                        </a>
                                        <a href="../delete/delete_course.php?id=' . $row['course_id'] . '" onclick="return confirm(\'Are you sure you want to delete this ' . $row['course_name'].'?\');">
                                                <button class="btn btn-danger fas fa-trash-alt"></button>
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