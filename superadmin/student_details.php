<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php');
    exit;
}

include '../includes/db.php';
include '../includes/header.php';

// Fetch student details
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $sql = "SELECT * FROM students WHERE student_id = '$student_id'";
    $result = mysqli_query($conn, $sql);
    $student = mysqli_fetch_assoc($result);
} else {
    echo "No student selected.";
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 d-none d-md-block col-lg-2 d-md-block bg-light fixed-top-10">
            <?php include '../includes/sidebar.php'; ?>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2>Student Details: <?php echo $student['first_name'] . ' ' . $student['last_name']; ?></h2>

            <div class="row">
                <div class="col-md-4">
                    <img src="../student_profile/<?php echo $student['profile']; ?>" alt="Profile Photo"
                        class="img-fluid img-thumbnail">
                </div>
                <div class="col-md-8">
                    <table class="table table-striped">
                        <tr>
                            <th>Student ID:</th>
                            <td><?php echo $student['student_id']; ?></td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                        </tr>

                        <tr>
                            <th>Email:</th>
                            <td><?php echo $student['email']; ?></td>
                        </tr>
                        <tr>
                            <th>Phone Number:</th>
                            <td><?php echo $student['phone_number']; ?></td>
                        </tr>
                        <tr>
                            <th>Course Name:</th>
                            <td><?php echo $student['course_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Date of Birth:</th>
                            <td><?php echo $student['date_of_birth']; ?></td>
                        </tr>
                        <tr>
                            <th>Gender:</th>
                            <td><?php echo $student['gender']; ?></td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td><?php echo $student['address']; ?></td>
                        </tr>
                        <tr>
                            <th>City:</th>
                            <td><?php echo $student['city']; ?></td>
                        </tr>
                        <tr>
                            <th>State:</th>
                            <td><?php echo $student['state']; ?></td>
                        </tr>
                        <tr>
                            <th>Postal Code:</th>
                            <td><?php echo $student['postal_code']; ?></td>
                        </tr>
                        <tr>
                            <th>Enrollment Date:</th>
                            <td><?php echo $student['enrollment_date']; ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td><?php echo $student['status']; ?></td>
                        </tr>
                        <tr>
                            <th>Fees Paid:</th>
                            <td><?php echo $student['fees_paid']; ?></td>
                        </tr>
                        <tr>
                            <th>Total Fees:</th>
                            <td><?php echo $student['total_fees']; ?></td>
                        </tr>
                        <tr>
                            <th>Balance Fees:</th>
                            <td><?php echo $student['balance_fees']; ?></td>
                        </tr>
                        <tr>
                            <th>Guardian Name:</th>
                            <td><?php echo $student['guardian_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Guardian Contact:</th>
                            <td><?php echo $student['guardian_contact']; ?></td>
                        </tr>
                        <tr>
                            <th>Remarks:</th>
                            <td><?php echo $student['remarks']; ?></td>
                        </tr>
                    </table>
                    <a href="edit_student.php?id=<?php echo $student['student_id']; ?>" class="btn btn-primary">Edit
                        Student</a>
                    <a href="../superadmin/students.php" class="btn btn-secondary">Back</a>

                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>