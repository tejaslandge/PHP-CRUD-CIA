<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php
include '../includes/db.php';

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

    // Handle form submission to update branch details
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $branch_name = $_POST['branch_name'];
        $branch_address = $_POST['branch_address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $contact_number = $_POST['contact_number'];
        $email = $_POST['email'];
        $branch_manager = $_POST['branch_manager'];
        $date_established = $_POST['date_established'];
        $status = $_POST['status'];
        $total_employees = $_POST['total_employees'];

        // Server-side validation
        $errors = [];
        
        if (!preg_match('/^\d+$/', $contact_number)) {
            $errors[] = "Contact Number must be numeric.";
        }
        
        if ($total_employees <= 0) {
            $errors[] = "Total Employees must be a positive number.";
        }

        if (empty($errors)) {
            // Update the branch details in the database
            $sql_update = "UPDATE branches SET branch_name=?, branch_address=?, city=?, state=?, contact_number=?, email=?, branch_manager=?, date_established=?, status=?, total_employees=? WHERE branch_id=?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssssssssssi", $branch_name, $branch_address, $city, $state, $contact_number, $email, $branch_manager, $date_established, $status, $total_employees, $branch_id);

            if ($stmt_update->execute()) {
                echo "Branch updated successfully!";
                header("Location:../superadmin/branches.php");
                exit;
            } else {
                echo "Error updating branch: " . $conn->error;
            }
        } else {
            // Output the errors
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }
    }
} else {
    echo "Invalid branch ID.";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Branch</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div style="position: sticky; top:0;">
        <?php include '../includes/header.php'; ?>
    </div>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar mt-5 position-fixed" style="top: 0;">
                <?php include '../includes/sidebar.php'; ?>
            </nav>
            <main class="col-12 col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <form method="POST" action="" class="p-4 bg-light rounded shadow-sm">
                    <h1>Edit Branch</h1>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="branch_name" class="form-label">Branch Name</label>
                            <input type="text" class="form-control" id="branch_name" name="branch_name"
                                value="<?php echo htmlspecialchars($branch['branch_name']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="branch_address" class="form-label">Branch Address</label>
                            <input type="text" class="form-control" id="branch_address" name="branch_address"
                                value="<?php echo htmlspecialchars($branch['branch_address']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city"
                                value="<?php echo htmlspecialchars($branch['city']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state"
                                value="<?php echo htmlspecialchars($branch['state']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="contact_number" name="contact_number"
                                pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number" maxlength="10"
                                inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required
                                value="<?php echo htmlspecialchars($branch['contact_number']); ?>" >
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?php echo htmlspecialchars($branch['email']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="branch_manager" class="form-label">Branch Manager</label>
                            <input type="text" class="form-control" id="branch_manager" name="branch_manager"
                                value="<?php echo htmlspecialchars($branch['branch_manager']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="date_established" class="form-label">Date Established</label>
                            <input type="date" class="form-control" id="date_established" name="date_established"
                                value="<?php echo htmlspecialchars($branch['date_established']); ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" <?php if ($branch['status'] == 'active') echo 'selected'; ?>>Active</option>
                                <option value="inactive" <?php if ($branch['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="total_employees" class="form-label">Total Employees</label>
                            <input type="number" class="form-control" id="total_employees" name="total_employees"
                                value="<?php echo htmlspecialchars($branch['total_employees']); ?>" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Branch</button>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<?php include '../includes/footer.php'; ?>
</html>
