
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ./superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>