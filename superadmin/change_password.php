<?php
session_start();
include '../includes/db.php'; 
include 'log_activity.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit();
}

$passstmt = ''; // Initialize message variable
$error_stmt = ''; // Initialize error message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the user's current password from the database
    $query = "SELECT password FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    // Check if the current password matches
    if ($current_password === $user['password']) {
        // Check if new password and confirm password match
        if ($new_password === $confirm_password) {
            // Update the new password in the database
            $update_query = "UPDATE users SET password = '$new_password' WHERE id = '$user_id'";
            if (mysqli_query($conn, $update_query)) {
                $_SESSION['passstmt'] = "<div class='alert alert-success'>Password updated successfully!</div>";
                // Instead of redirecting immediately, set a flag to show the message and handle redirection via JavaScript
                header("Location: ../superadmin/my_profile.php?delay=5");
                exit();
            } else {
                $error_stmt = "<div class='alert alert-danger'>Error updating password.</div>";
            }
        } else {
            $error_stmt = "<div class='alert alert-danger'>New password and confirmation password do not match.</div>";
        }
    } else {
        $error_stmt = "<div class='alert alert-danger'>Current password is incorrect.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div style="position: sticky; top:0;">
        <?php include '../includes/header.php'; ?>
    </div>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar position-sticky" style="top: 0;">
                <?php include '../includes/sidebar.php'; ?>
            </nav>
            <main class="col-12 col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container mt-5">
                    <h2>Change Password</h2>
                    
                    <!-- Display the success message -->
                    <?php if (isset($_SESSION['passstmt'])): ?>
                        <?php echo $_SESSION['passstmt']; ?>
                        <?php unset($_SESSION['passstmt']); // Clear the message after displaying it ?>
                    <?php endif; ?>

                    <!-- Display error messages -->
                    <?php if (!empty($error_stmt)): ?>
                        <?php echo $error_stmt; ?>
                    <?php endif; ?>

                    <form action="change_password.php" method="POST">
                        <div class="mb-3" style="width: 40%;">
                            <label for="current_password" class="form-label">Current Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <span class="input-group-text toggle-password" data-target="#current_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3" style="width: 40%;">
                            <label for="new_password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                                <span class="input-group-text toggle-password" data-target="#new_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mb-3" style="width: 40%;">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                <span class="input-group-text toggle-password" data-target="#confirm_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
<?php include '../includes/footer.php'; ?>
<script>
    document.querySelectorAll('.toggle-password').forEach(item => {
        item.addEventListener('click', function () {
            const target = document.querySelector(this.getAttribute('data-target'));
            const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
            target.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });

    // Check for the delay parameter in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const delay = urlParams.get('delay');
    if (delay) {
        setTimeout(() => {
            window.location.href = '../superadmin/my_profile.php'; // Redirect after 5 seconds
        }, delay * 1000); // Convert seconds to milliseconds
    }
</script>
</html>
