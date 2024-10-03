<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}

// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error", 0);

function error_display($errno, $errstr, $errfile, $errline)
{
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL, 3, "../error/error_log.txt");
}
set_error_handler("error_display");

// Include your database connection file
include '../includes/db.php';
include '../superadmin/log_activity.php';



// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize inputs to prevent SQL injection
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $enrollment_date = mysqli_real_escape_string($conn, $_POST['enrollment_date']);
    $guardian_name = mysqli_real_escape_string($conn, $_POST['guardian_name']);
    $guardian_contact = mysqli_real_escape_string($conn, $_POST['guardian_contact']);
    $fees_paid = mysqli_real_escape_string($conn, $_POST['fees_paid']);
    $total_fees = mysqli_real_escape_string($conn, $_POST['total_fees']);
    $balance_fees = $total_fees - $fees_paid; // Calculate balance fees
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    // Handling file upload for profile picture
    $profile_picture = '';
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $profile_picture = $_FILES['profile']['name'];
        $tmp_name = $_FILES['profile']['tmp_name'];
        $uploads_dir = '../student_profile/'; // You need to create this folder
        move_uploaded_file($tmp_name, "$uploads_dir/$profile_picture");
    }

    // Insert data into the database
    $sql = "INSERT INTO students (first_name, last_name, email, phone_number, course_name, date_of_birth, gender, address, city, state, postal_code, enrollment_date, guardian_name, guardian_contact, fees_paid, total_fees, balance_fees, profile, remarks)
            VALUES ('$first_name', '$last_name', '$email', '$phone_number', '$course_name', '$date_of_birth', '$gender', '$address', '$city', '$state', '$postal_code', '$enrollment_date', '$guardian_name', '$guardian_contact', '$fees_paid', '$total_fees', '$balance_fees', '$profile_picture', '$remarks')";

    if (mysqli_query($conn, $sql)) {
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            logActivity($_SESSION['user_id'], $_SESSION['username'], "Add data of Student:$first_name $last_name");
        }
        $_SESSION['addstd']= "<div class='alert alert-success'>Student added successfully!</div>";
        header("location:../superadmin/students.php");
    } else {
        $_SESSION['addstd']= "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

</head>
<?php include('../includes/header.php'); ?>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar position-sticky"
                style="top: 0;">
                <?php include '../includes/sidebar.php'; ?>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container mt-0">
                    <h2 class="">Add New Student</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="course_name" class="form-label">Course Name</label>
                                <input type="text" id="course_name" name="course_name" class="form-control" required
                                    list="course_list">
                                <datalist id="course_list">
                                    <!-- Options will be populated dynamically -->
                                </datalist>
                            </div>
                            <div class="col">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" class="form-control" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="city" class="form-label">City</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="col ">
                                <label for="state" class="form-label">State</label>
                                <input type="text" name="state" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="postal_code" class="form-label">Postal Code</label>
                                <input type="text" name="postal_code" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="enrollment_date" class="form-label">Enrollment Date</label>
                                <input type="date" name="enrollment_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="guardian_name" class="form-label">Guardian Name</label>
                                <input type="text" name="guardian_name" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="guardian_contact" class="form-label">Guardian Contact</label>
                                <input type="text" name="guardian_contact" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="total_fees" class="form-label">Total Fees</label>
                                <input type="number" id="total_fees" name="total_fees" class="form-control" required
                                    oninput="calculateBalance()">
                            </div>
                            <div class="col">
                                <label for="fees_paid" class="form-label">Fees Paid</label>
                                <input type="number" id="fees_paid" name="fees_paid" class="form-control" required
                                    oninput="calculateBalance()">
                            </div>
                            <div class="col">
                                <label for="balance_fees" class="form-label">Balance Fees</label>
                                <input type="number" id="balance_fees" name="balance_fees" class="form-control"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="profile" class="form-label">Profile Picture</label>
                                <input type="file" name="profile" class="form-control" accept="image/*">
                            </div>
                            <div class="col">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mb-3">Add Student</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
<?php
include '../includes/footer.php';
?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetchCourses();
    });

    function fetchCourses() {
        fetch('../add/fetch_courses.php')
            .then(response => response.json())
            .then(data => {
                const datalist = document.getElementById('course_list');
                datalist.innerHTML = ''; // Clear existing options

                data.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course;
                    datalist.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching courses:', error));
    }

    function calculateBalance() {
        const feesPaid = parseFloat(document.getElementById('fees_paid').value) || 0;
        const totalFees = parseFloat(document.getElementById('total_fees').value) || 0;
        const balanceFees = totalFees - feesPaid;
        document.getElementById('balance_fees').value = balanceFees.toFixed(2); // Display 2 decimal places
    }
</script>