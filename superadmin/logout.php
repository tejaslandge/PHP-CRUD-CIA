<?php
// logout.php

session_start();
include '../superadmin/log_activity.php';

if (isset($_SESSION['user_id'])) {
    // Log logout action
    logActivity($_SESSION['user_id'], $_SESSION['username'], "User logged out");

    // Destroy the session
    session_destroy();
    header("Location: ../superadmin/login_form.php");
    exit();
}
?>
