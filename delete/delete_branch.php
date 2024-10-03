<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php

include '../includes/db.php';
include '../superadmin/log_activity.php';

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    logActivity($_SESSION['user_id'], $_SESSION['username'], "Delete data of Branch:$branch_name");
} else {
    error_log("User not logged in or missing session data for logging.");
}

// Check if branch_id is provided via GET request
if (isset($_GET['id'])) {
    $branch_id = $_GET['id'];

    // Prepare a SQL statement to delete the branch
    $sql = "DELETE FROM branches WHERE branch_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $branch_id);

    // Try executing the query
    if ($stmt->execute()) {
        $_SESSION['delbranch']="<div class='alert alert-success'>Delete Branch successfully!</div>";
        // If successfully deleted, redirect to the branches page with a success message
        header("Location: ../superadmin/branches.php?message=Branch successfully deleted");
        exit();
    } else {
        // If there's an error, display an error message
        $_SESSION['delbranch']= "Error deleting branch: " . $conn->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "Invalid branch ID.";
}

// Close the database connection
$conn->close();
?>
