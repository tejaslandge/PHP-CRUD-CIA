<?php
// logout.php

session_start();
include '../superadmin/log_activity.php';

if (isset($_SESSION['user_id'])) {
    
    // Log logout action before destroying the session
    logActivity($_SESSION['user_id'], $_SESSION['username'], "User logged out");
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header("Location: ../superadmin/login_form.php");
    exit();
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: ../superadmin/login_form.php");
    exit();
}
?>
