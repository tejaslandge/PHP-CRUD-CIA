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

logActivity($_SESSION['user_id'], $_SESSION['username'], "Viewed Branch Details");

// Check if branch_id is provided in the URL
if (isset($_GET['id'])) {
    $branch_id = $_GET['id'];

    // Fetch the branch details from the database
    $sql = "SELECT * FROM branches WHERE branch_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $branch_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the branch exists
    if ($result->num_rows > 0) {
        $branch = $result->fetch_assoc();
    } else {
        echo "Branch not found.";
        exit;
    }
} else {
    echo "Invalid branch ID.";
    exit;
}
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
                <h1 class="mt-5">Branch Details</h1>

                <table class="table table-bordered mt-4">
                    <tr>
                        <th>Branch ID</th>
                        <td><?php echo htmlspecialchars($branch['branch_id']); ?></td>
                    </tr>
                    <tr>
                        <th>Branch Name</th>
                        <td><?php echo htmlspecialchars($branch['branch_name']); ?></td>
                    </tr>
                    <tr>
                        <th>Branch Address</th>
                        <td><?php echo htmlspecialchars($branch['branch_address']); ?></td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td><?php echo htmlspecialchars($branch['city']); ?></td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td><?php echo htmlspecialchars($branch['state']); ?></td>
                    </tr>
                    <tr>
                        <th>Contact Number</th>
                        <td><?php echo htmlspecialchars($branch['contact_number']); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo htmlspecialchars($branch['email']); ?></td>
                    </tr>
                    <tr>
                        <th>Branch Manager</th>
                        <td><?php echo htmlspecialchars($branch['branch_manager']); ?></td>
                    </tr>
                    <tr>
                        <th>Date Established</th>
                        <td><?php echo htmlspecialchars($branch['date_established']); ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php
                            if ($branch['status'] == 'active') {
                                echo '<span class="badge bg-success">Active</span>';
                            } else {
                                echo '<span class="badge bg-danger">Inactive</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Employees</th>
                        <td><?php echo htmlspecialchars($branch['total_employees']); ?></td>
                    </tr>
                </table>

                <a href="branches.php" class="btn btn-primary mb-3">Back</a>

            </div>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>