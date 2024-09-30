<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}

include '../includes/db.php'; // Include database connection

// Fetch the trainer's data based on trainer_id from the URL
if (isset($_GET['trainer_id'])) {
    $trainer_id = $_GET['trainer_id'];

    // SQL query to fetch trainer data
    $sql = "SELECT * FROM trainers WHERE trainer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $trainer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the trainer exists
    if ($result->num_rows > 0) {
        $trainer = $result->fetch_assoc();
    } else {
        echo "Trainer not found.";
        exit;
    }
} else {
    echo "No trainer selected.";
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <!-- Header -->
    <div class="sticky-top">
        <?php include '../includes/header.php'; ?>
    </div>

    <!-- Main Container -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 bg-light sidebar pt-3">
                <?php include '../includes/sidebar.php'; ?>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
               
                <!-- Trainer Profile Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3><?php echo $trainer['first_name'] . ' ' . $trainer['last_name']; ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Trainer's Photo -->
                            <div class="col-md-4">
                                <img src="../trainer_profile/<?php echo $trainer['photo']; ?>" alt="Profile Photo" class="img-fluid img-thumbnail">
                            </div>

                            <!-- Trainer's Details -->
                            <div class="col-md-8">
                                <p><strong>Email:</strong> <?php echo $trainer['email']; ?></p>
                                <p><strong>Phone Number:</strong> <?php echo $trainer['phone_number']; ?></p>
                                <p><strong>Expertise:</strong> <?php echo $trainer['expertise']; ?></p>
                                <p><strong>Qualification:</strong> <?php echo $trainer['qualification']; ?></p>
                                <p><strong>Experience:</strong> <?php echo $trainer['experience_years']; ?> years</p>
                                <p><strong>Joining Date:</strong> <?php echo $trainer['joining_date']; ?></p>
                                <p><strong>Branch:</strong> <?php echo $trainer['trainer_branch']; ?></p>
                                <p><strong>Salary:</strong> <?php echo $trainer['salary']; ?></p>
                                <p><strong>Address:</strong> <?php echo $trainer['address']; ?></p>
                                <p><strong>Date of Birth:</strong> <?php echo $trainer['date_of_birth']; ?></p>
                                <p><strong>Gender:</strong> <?php echo $trainer['gender']; ?></p>
                                <p><strong>Bio:</strong> <?php echo $trainer['trainer_bio']; ?></p>
                                <p><strong>Certifications:</strong> <?php echo $trainer['certifications']; ?></p>
                                <p><strong>Availability Schedule:</strong> <?php echo $trainer['availability_schedule']; ?></p>
                                <p><strong>Status:</strong> <?php if ($trainer['status'] == 'active') {
                                    echo '<span class="badge bg-success">Active</span>';
                                } else {
                                    echo '<span class="badge bg-danger">Inactive</span>';
                                }?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Back Button -->
                <a href="../superadmin/trainers.php" class="btn btn-secondary">Back</a>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

<?php include '../includes/footer.php'; ?>
