<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}

$host = 'localhost';    // Database host
$user = 'root';         // Database username
$password = '';         // Database password
$database = 'ciadb2';   // Database name

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current logged-in username from the session
$username = $_SESSION['username'];

// Query to fetch user details from the users table for the logged-in user
$sql = "SELECT username, email FROM users WHERE username = '$username'";
$result = $conn->query($sql);

$userData = [];
if ($result && $result->num_rows > 0) {
    // Fetch the user's data
    $userData = $result->fetch_assoc();
} else {
    echo "No user found!";
}

// Close the connection
$conn->close();


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

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <div class="container mt-5">
                    <?php if (isset($_SESSION['passstmt'])): ?>
                        <?php echo $_SESSION['passstmt']; ?>
                        <?php unset($_SESSION['passstmt']); // Clear the message after displaying it ?>
                    <?php endif; ?>
                    <h2>Admin Profile</h2>
                    <a href="../superadmin/change_password.php"><button class="btn btn-primary">Change
                            Password</button></a>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Profile Details</h5>
                            <!-- Display the user's profile details -->
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['username']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>