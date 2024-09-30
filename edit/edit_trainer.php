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
set_error_handler(callback: "error_display");


include '../includes/db.php';
include '../superadmin/log_activity.php';

// Retrieve the trainer ID from the URL
if (isset($_GET['trainer_id'])) {
    $trainer_id = $_GET['trainer_id'];

    // Fetch the trainer's current details
    $sql_fetch = "SELECT * FROM trainers WHERE trainer_id = ?";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bind_param("i", $trainer_id);
    $stmt_fetch->execute();
    $result = $stmt_fetch->get_result();

    if ($result->num_rows > 0) {
        $trainer = $result->fetch_assoc();
    } else {
        echo "Trainer not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

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
    $photo_path = $trainer['photo']; // Keep the existing photo by default

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
            echo "Error uploading photo.";
        }
    }

    // Correct SQL Query for update
    $sql_update = "UPDATE trainers SET 
        first_name = ?, 
        last_name = ?, 
        email = ?, 
        phone_number = ?, 
        expertise = ?, 
        qualification = ?, 
        experience_years = ?, 
        joining_date = ?, 
        trainer_branch = ?, 
        salary = ?, 
        photo = ?, 
        address = ?, 
        date_of_birth = ?, 
        gender = ?, 
        trainer_bio = ?, 
        certifications = ?, 
        availability_schedule = ?, 
        status = ? 
    WHERE trainer_id = ?";

    // Prepare the statement
    $stmt_update = $conn->prepare($sql_update);

    if (!$stmt_update) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    // Bind parameters
    $stmt_update->bind_param(
        "ssssssississssssssi",
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
        $status,
        $trainer_id // Add trainer_id for WHERE clause
    );

    // Execute the statement and check for success
    if ($stmt_update->execute()) {
        // Log activity
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            logActivity($_SESSION['user_id'], $_SESSION['username'], "Updated trainer: $first_name $last_name");
        }
        // Redirect to trainers page
        header("Location: ../superadmin/trainers.php");
        exit();
    } else {
        echo "Error updating trainer: " . $stmt_update->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Trainer Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div style="position: sticky; top:0;">
        <?php include '../includes/header.php'; ?>
    </div>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar position-sticky"
                style="top: 0;">
                <?php include '../includes/sidebar.php'; ?>
            </nav>
            <main class="col-12 col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container ">
                    <h2 class="mb-4">Edit Trainer Details</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    value="<?php echo htmlspecialchars($trainer['first_name']); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    value="<?php echo htmlspecialchars($trainer['last_name']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo htmlspecialchars($trainer['email']); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                    value="<?php echo htmlspecialchars($trainer['phone_number']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="expertise" class="form-label">Expertise</label>
                                <input type="text" class="form-control" id="expertise" name="expertise"
                                    value="<?php echo htmlspecialchars($trainer['expertise']); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="qualification" class="form-label">Qualification</label>
                                <input type="text" class="form-control" id="qualification" name="qualification"
                                    value="<?php echo htmlspecialchars($trainer['qualification']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="experience_years" class="form-label">Experience (Years)</label>
                                <input type="number" class="form-control" id="experience_years" name="experience_years"
                                    value="<?php echo htmlspecialchars($trainer['experience_years']); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="joining_date" class="form-label">Joining Date</label>
                                <input type="date" class="form-control" id="joining_date" name="joining_date"
                                    value="<?php echo htmlspecialchars($trainer['joining_date']); ?>" required>
                            </div>
                        </div>
                        <div class="row">
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

                            <div class="col-md-6 mb-3">
                                <label for="salary" class="form-label">Salary</label>
                                <input type="number" class="form-control" id="salary" name="salary"
                                    value="<?php echo htmlspecialchars($trainer['salary']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                <img src="../trainer_profile/<?php echo htmlspecialchars($trainer['photo']); ?>"
                                    alt="Trainer Photo" class="img-thumbnail mt-2" width="150">
                            </div>
                        
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2"
                                    required><?php echo htmlspecialchars($trainer['address']); ?></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                    value="<?php echo htmlspecialchars($trainer['date_of_birth']); ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="Male" <?php echo ($trainer['gender'] == 'Male') ? 'selected' : ''; ?>>
                                        Male</option>
                                    <option value="Female" <?php echo ($trainer['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo ($trainer['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="trainer_bio" class="form-label">Trainer Bio</label>
                                <textarea class="form-control" id="trainer_bio" name="trainer_bio" rows="3"
                                    required><?php echo htmlspecialchars($trainer['trainer_bio']); ?></textarea>
                            </div>
                       
                            <div class="col-md-6 mb-3">
                                <label for="certifications" class="form-label">Certifications</label>
                                <input type="text" class="form-control" id="certifications" name="certifications"
                                    value="<?php echo htmlspecialchars($trainer['certifications']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="availability_schedule" class="form-label">Availability Schedule</label>
                                <textarea class="form-control" id="availability_schedule" name="availability_schedule"
                                    rows="3"
                                    required><?php echo htmlspecialchars($trainer['availability_schedule']); ?></textarea>
                            </div>
                        
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" <?php echo ($trainer['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($trainer['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mb-5 ">Update Trainer</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <?php
    include '../includes/footer.php';
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>