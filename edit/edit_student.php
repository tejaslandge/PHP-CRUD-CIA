<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php');
    exit;
}

include '../includes/db.php';
include '../includes/header.php';
include '../superadmin/log_activity.php';




// Fetch student details to edit
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $sql = "SELECT * FROM students WHERE student_id = '$student_id'";
    $result = mysqli_query($conn, $sql);
    $student = mysqli_fetch_assoc($result);
}

// Update student information including profile photo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $course_name = $_POST['course_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $enrollment_date = $_POST['enrollment_date'];
    $status = $_POST['status'];
    $fees_paid = $_POST['fees_paid'];
    $total_fees = $_POST['total_fees'];
    $balance_fees = $_POST['balance_fees'];
    $guardian_name = $_POST['guardian_name'];
    $guardian_contact = $_POST['guardian_contact'];
    $remarks = $_POST['remarks'];

    // Handle profile photo upload
    $profile_photo = $student['profile']; // Keep the existing photo by default
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] == 0) {
        $profile_photo = $_FILES['profile']['name'];
        $target_directory = "../student_profile/";
        $target_file = $target_directory . basename($profile_photo);
        move_uploaded_file($_FILES['profile']['tmp_name'], $target_file);
    }

    // Update query
    $update_sql = "UPDATE students SET 
        first_name = '$first_name', 
        last_name = '$last_name',
        email = '$email', 
        phone_number = '$phone_number',
        course_name = '$course_name',
        date_of_birth = '$date_of_birth',
        gender = '$gender',
        address = '$address',
        city = '$city',
        state = '$state',
        postal_code = '$postal_code',
        enrollment_date = '$enrollment_date',
        status = '$status',
        fees_paid = '$fees_paid',
        total_fees = '$total_fees',
        balance_fees = '$balance_fees',
        guardian_name = '$guardian_name',
        guardian_contact = '$guardian_contact',
        profile = '$profile_photo',
        remarks = '$remarks'
        WHERE student_id = '$student_id'";

    if (mysqli_query($conn, $update_sql)) {
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            logActivity($_SESSION['user_id'], $_SESSION['username'], "Update  Details of Student : $first_name $last_name");
        }
        $_SESSION['editstd']="<div class='alert alert-success'>Update Student details successfully!</div>";
        // header('Location: ../superadmin/students.php');
        echo "<script>window.location.href = '../superadmin/students.php';</script>";
    } else {
        $_SESSION['editstd']= "Error updating record: " . mysqli_error($conn);
    }
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
            <h2>Edit Student</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                            value="<?php echo $student['first_name']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            value="<?php echo $student['last_name']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo $student['email']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number"
                            value="<?php echo $student['phone_number']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="course_name" class="form-label">Course Name</label>
                        <input type="text" class="form-control" id="course_name" name="course_name"
                            value="<?php echo $student['course_name']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                            value="<?php echo $student['date_of_birth']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="Male" <?php if ($student['gender'] == 'Male')
                                echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if ($student['gender'] == 'Female')
                                echo 'selected'; ?>>Female
                            </option>
                            <option value="Other" <?php if ($student['gender'] == 'Other')
                                echo 'selected'; ?>>Other
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address"
                            value="<?php echo $student['address']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city"
                            value="<?php echo $student['city']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control" id="state" name="state"
                            value="<?php echo $student['state']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control" id="postal_code" name="postal_code"
                            value="<?php echo $student['postal_code']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="enrollment_date" class="form-label">Enrollment Date</label>
                        <input type="date" class="form-control" id="enrollment_date" name="enrollment_date"
                            value="<?php echo $student['enrollment_date']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="total_fees" class="form-label">Total Fees</label>
                        <input type="number" class="form-control" id="total_fees" name="total_fees"
                            value="<?php echo $student['total_fees']; ?>" required oninput="calculateBalance()">
                    </div>
                    <div class="col-md-4">
                        <label for="fees_paid" class="form-label">Fees Paid</label>
                        <input type="number" class="form-control" id="fees_paid" name="fees_paid"
                            value="<?php echo $student['fees_paid']; ?>" required oninput="calculateBalance()">
                    </div>

                    <div class="col-md-4">
                        <label for="balance_fees" class="form-label">Balance Fees</label>
                        <input type="number" class="form-control" id="balance_fees" name="balance_fees"
                            value="<?php echo $student['balance_fees']; ?>" required readonly>
                    </div>
                </div>




                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="guardian_name" class="form-label">Guardian Name</label>
                        <input type="text" class="form-control" id="guardian_name" name="guardian_name"
                            value="<?php echo $student['guardian_name']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="guardian_contact" class="form-label">Guardian Contact</label>
                        <input type="text" class="form-control" id="guardian_contact" name="guardian_contact"
                            value="<?php echo $student['guardian_contact']; ?>" required>
                    </div>

                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Active" <?php if ($student['status'] == 'Active')
                                echo 'selected'; ?>>Active
                            </option>
                            <option value="Inactive" <?php if ($student['status'] == 'Inactive')
                                echo 'selected'; ?>>Inactive
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"
                            required><?php echo $student['remarks']; ?></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="profile" class="form-label">Profile Photo</label>
                        <input type="file" class="form-control" id="profile" name="profile">
                        <?php if (!empty($student['profile'])): ?>
                            <img src="../student_profile/<?php echo $student['profile']; ?>" alt="Profile Photo"
                                class="img-thumbnail" width="150">
                        <?php endif; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Student</button>
                <a href="../superadmin/students.php" class="btn btn-secondary">Back</a>
            </form>
        </main>
    </div>
</div>
<script>
    function calculateBalance() {
        var feesPaid = document.getElementById('fees_paid').value;
        var totalFees = document.getElementById('total_fees').value;

        var balanceFees = totalFees - feesPaid;

        document.getElementById('balance_fees').value = balanceFees;
    }
</script>
<?php include '../includes/footer.php'; ?>