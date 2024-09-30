<?php
// log_activity.php

// Include your database connection file
include '../includes/db.php'; // Adjust the path as necessary

function logActivity($user_id, $username, $action) {
    global $conn;
    
    // Check if the connection is valid
    if ($conn === null) {
        die("Database connection failed.");
    }
    
    // Prepare the SQL statement
    $sql = "INSERT INTO activity_log (user_id, username, action, log_time) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Failed to prepare the SQL statement.");
    }
    
    // Bind the parameters and execute the statement
    $stmt->bind_param("iss", $user_id, $username, $action);
    $stmt->execute();
    $stmt->close();
}

?>
