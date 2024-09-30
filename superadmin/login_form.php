<?php
session_start();
include '../includes/db.php';
include '../superadmin/log_activity.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE (username = '$username' OR email = '$username')AND password = '$password' AND status = 'active'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id']; // Store the user ID in the session
        $_SESSION['username'] = $row['username']; // Store the username in the session
        logActivity($_SESSION['user_id'], $_SESSION['username'], "User logged in");

        header('Location: ../superadmin/dashboard.php'); // Redirect to the profile page

        exit();
    } else {
        echo "Invalid login credentials";
    }
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



<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome for Eye Icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="login-container">
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter username"
                    required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password"
                        placeholder="Enter password" required>
                    <span class="input-group-text" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<style>
    body,
    html {
        height: 100%;
    }

    body {
        background: url('../assets/cia1.png') no-repeat center center fixed;
        background-size: cover;
    }

    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-box {
        background-color: rgba(255, 255, 255, 0.9);
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        max-width: 400px;
        width: 100%;
    }

    .login-box h2 {
        margin-bottom: 20px;
        text-align: center;
    }

    .btn-primary {
        width: 100%;
    }

    .form-control {
        border-radius: 10px;
    }

    .input-group-text {
        cursor: pointer;
    }
</style>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const passwordFieldType = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', passwordFieldType);
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
</script>