<?php
session_start(); // Start session

// Include database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "ciadb2"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['username']; 
    $password = $_POST['password'];
    
    if ($input != "" && $password != "") { 
        // Prepare and bind
        $stmt = $conn->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND password = ?");
        $stmt->bind_param("sss", $input, $input, $password);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User found
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $input; 
            header("Location: ../superadmin/dashboard.php"); 
            exit;
        } else {
            $error="Invalid username/email or password.";
        }
    } else {
        echo "<script>alert('Please fill both the Username and Password fields');</script>";
    }

    $stmt->close();
}
$conn->close();
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
                <label for="username" class="form-label">Username or Email</label>
                <input type="text" class="form-control" name="username" id="username"
                    placeholder="Enter username or email" required>
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