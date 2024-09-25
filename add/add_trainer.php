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
include '../superadmin/log_activity.php';

    ?>

<?php
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
    $branch_id = $_POST['branch_id'];
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

        // Get file extension
        $file_extension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);

        // Generate unique file name
        $unique_file_name = uniqid("trainer_", true) . '.' . $file_extension;

        // Specify the target file path
        $target_file = $target_dir . $unique_file_name;

        // Move the file to the target directory
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_path = $target_file;
        } else {
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
        branch_id,  
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
        $branch_id,
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
        echo "Trainer added successfully!";
        logActivity($_SESSION['user_id'], $_SESSION['username'], "Add Trainer Data");

        header("Location: ../superadmin/trainers.php");
    } else {
        echo "Error adding trainer: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Trainer Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar position-sticky" style="top: 0;">
                <?php include '../includes/sidebar.php'; ?>
            </nav>
            <div class="container mt-5">
                <h2>Add Trainer Details</h2>
                <form action="" method="POST" enctype="multipart/form-data">
                    <!-- Form Fields -->
                    <!-- First Name -->
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <!-- Last Name -->
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <!-- Phone Number -->
                    <div class="form-group">
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                    </div>
                    <!-- Expertise -->
                    <div class="form-group">
                        <label for="expertise">Expertise:</label>
                        <input type="text" class="form-control" id="expertise" name="expertise" required>
                    </div>
                    <!-- Qualification -->
                    <div class="form-group">
                        <label for="qualification">Qualification:</label>
                        <input type="text" class="form-control" id="qualification" name="qualification" required>
                    </div>
                    <!-- Experience in Years -->
                    <div class="form-group">
                        <label for="experience_years">Experience (in years):</label>
                        <input type="number" class="form-control" id="experience_years" name="experience_years" required>
                    </div>
                    <!-- Joining Date -->
                    <div class="form-group">
                        <label for="joining_date">Joining Date:</label>
                        <input type="date" class="form-control" id="joining_date" name="joining_date" required>
                    </div>
                    <!-- Branch ID -->
                    <div class="form-group">
                        <label for="branch_id">Branch ID:</label>
                        <input type="number" class="form-control" id="branch_id" name="branch_id" required>
                    </div>
                    <!-- Salary -->
                    <div class="form-group">
                        <label for="salary">Salary:</label>
                        <input type="number" class="form-control" id="salary" name="salary" required>
                    </div>
                    <!-- Photo -->
                    <div class="form-group">
                        <label for="photo">Photo:</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                    </div>
                    <!-- Address -->
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>
                    <!-- Date of Birth -->
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth:</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                    </div>
                    <!-- Gender -->
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <!-- Trainer Bio -->
                    <div class="form-group">
                        <label for="trainer_bio">Trainer Bio:</label>
                        <textarea class="form-control" id="trainer_bio" name="trainer_bio" rows="4" required></textarea>
                    </div>
                    <!-- Certifications -->
                    <div class="form-group">
                        <label for="certifications">Certifications:</label>
                        <textarea class="form-control" id="certifications" name="certifications" rows="3" required></textarea>
                    </div>
                    <!-- Availability Schedule -->
                    <div class="form-group">
                        <label for="availability_schedule">Availability Schedule:</label>
                        <textarea class="form-control" id="availability_schedule" name="availability_schedule" rows="3" required></textarea>
                    </div>
                    <!-- Status -->
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
