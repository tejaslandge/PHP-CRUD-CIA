<?php
// Start the session
session_start();

// Unset all of the session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: ../superadmin/login_form.php'); // Redirect to login page
exit();
?>
