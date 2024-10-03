<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error",0);

function error_display($errno, $errstr, $errfile, $errline){
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL,3,"../error/error_log.txt");
}
set_error_handler(callback: "error_display");


include '../includes/db.php';
include '../superadmin/log_activity.php';

// Process the form if it is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data using POST method
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $expertise = $_POST['expertise'];
    $qualification = $_POST['qualification'];
    $experience_years = $_POST['experience_years'];
    $joining_date = $_POST['joining_date'];
    $trainer_branch = $_POST['trainer_branch'];
    $salary = $_POST['salary'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $trainer_bio = $_POST['trainer_bio'];
    $certifications = $_POST['certifications'];
    $availability_schedule = $_POST['availability_schedule'];
    $status = $_POST['status'];

    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Specify the directory where the file will be saved
        $target_dir = "../trainer_profile/";

        // Ensure the target directory exists
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Get file extension
        $file_extension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);

        // Generate unique file name
        $unique_file_name = uniqid("trainer_", true) . '.' . $file_extension;

        // Specify the target file path
        $target_file = $target_dir . $unique_file_name;

        // Move the file to the target directory
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_path = $unique_file_name; // Store only the filename, not the full path
        } else {
            $photo_path = null;
            echo "Error uploading photo.";
        }
    } else {
        $photo_path = null; // No photo uploaded
    }

    // Correct SQL Query
    $sql_insert = "INSERT INTO trainers (
        first_name, 
        last_name, 
        email, 
        phone_number, 
        expertise, 
        qualification, 
        experience_years, 
        joining_date, 
        trainer_branch,  
        salary, 
        photo, 
        address, 
        date_of_birth, 
        gender, 
        trainer_bio, 
        certifications, 
        availability_schedule, 
        status
    ) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt_insert = $conn->prepare($sql_insert);

    if (!$stmt_insert) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    // Bind parameters
    $stmt_insert->bind_param(
        "ssssssississssssss",
        $first_name,
        $last_name,
        $email,
        $phone_number,
        $expertise,
        $qualification,
        $experience_years,
        $joining_date,
        $trainer_branch,
        $salary,
        $photo_path,
        $address,
        $date_of_birth,
        $gender,
        $trainer_bio,
        $certifications,
        $availability_schedule,
        $status
    );

    // Execute the statement and check for success
    if ($stmt_insert->execute()) {
        // Log activity
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            logActivity($_SESSION['user_id'], $_SESSION['username'], "Added a new trainer: $first_name $last_name");
        }
        $_SESSION['addtrainer']= "<div class='alert alert-success'>Trainer added successfully!</div>";

        // Redirect to trainers page
        header("Location: ../superadmin/trainers.php");
        exit();
    } else {
        $_SESSION['addtrainer']= "<div class='alert alert-danger'>Error adding trainer: " . $stmt_insert->error ."</div>";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Trainer Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div style="position: sticky; top:0;">
        <?php include '../includes/header.php'; ?>
    </div>

    <!-- Dashboard Container -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar position-sticky"
                style="top: 0;">
                <?php include '../includes/sidebar.php'; ?>
            </nav>
            <main class="col-12 col-md-9 ms-sm-auto col-lg-10 px-md-4">

                <div class="container ">
                    <h2 class="mb-4">Add New Trainer</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <!-- First Name -->
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    placeholder="Enter first name" required>
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    placeholder="Enter last name" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter email" required>
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="Enter phone number" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Expertise -->
                            <div class="col-md-6 mb-3">
                                <label for="expertise" class="form-label">Expertise</label>
                                <input type="text" class="form-control" id="expertise" name="expertise"
                                    placeholder="Enter expertise (e.g., Web Development, Data Science)" required>
                            </div>

                            <!-- Qualification -->
                            <div class="col-md-6 mb-3">
                                <label for="qualification" class="form-label">Qualification</label>
                                <input type="text" class="form-control" id="qualification" name="qualification"
                                    placeholder="Enter qualification" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Experience in Years -->
                            <div class="col-md-6 mb-3">
                                <label for="experience_years" class="form-label">Experience (Years)</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years"
                                    placeholder="Enter years of experience" required>
                            </div>

                            <!-- Joining Date -->
                            <div class="col-md-6 mb-3">
                                <label for="joining_date" class="form-label">Joining Date</label>
                                <input type="date" class="form-control" id="joining_date" name="joining_date" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Trainer Branch -->
                            <div class="col-md-6 mb-3">
                                <label for="trainer_branch" class="form-label">Trainer Branch</label>
                                <select class="form-control" id="trainer_branch" name="trainer_branch" required>
                                    <option value="">Select Branch</option>
                                    <?php
                                    // Fetch branches from the branches table
                                    $branch_query = "SELECT branch_id, branch_name FROM branches WHERE status = 'active'";
                                    $branch_result = $conn->query($branch_query);

                                    // Loop through each branch and create an option tag
                                    if ($branch_result->num_rows > 0) {
                                        while ($branch = $branch_result->fetch_assoc()) {
                                            echo '<option value="' . $branch['branch_name'] . '">' . $branch['branch_name'] . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">No branches available</option>';
                                    }
                                    ?>
                                </select>
                            </div>


                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Salary -->
                            <div class="col-md-6 mb-3">
                                <label for="salary" class="form-label">Salary</label>
                                <input type="number" class="form-control" id="salary" name="salary"
                                    placeholder="Enter salary" required>
                            </div>

                            <!-- Photo -->
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Address -->
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" placeholder="Enter address"
                                    required></textarea>
                            </div>

                            <!-- Date of Birth -->
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Gender -->
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Trainer Bio -->
                            <div class="col-md-6 mb-3">
                                <label for="trainer_bio" class="form-label">Trainer Bio</label>
                                <textarea class="form-control" id="trainer_bio" name="trainer_bio"
                                    placeholder="Enter brief bio" required></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Certifications -->
                            <div class="col-md-6 mb-3">
                                <label for="certifications" class="form-label">Certifications</label>
                                <input type="text" class="form-control" id="certifications" name="certifications"
                                    placeholder="Enter certifications" required>
                            </div>

                            <!-- Availability Schedule -->
                            <div class="col-md-6 mb-3">
                                <label for="availability_schedule" class="form-label">Availability Schedule</label>
                                <input type="text" class="form-control" id="availability_schedule"
                                    name="availability_schedule" placeholder="Enter availability schedule" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mb-3">Add Trainer</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <?php
    include '../includes/footer.php';
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/js/bootstrap.min.js"></script>
</body>

</html>