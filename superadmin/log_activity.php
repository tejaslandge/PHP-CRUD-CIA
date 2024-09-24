<?php
// log_activity.php

function logActivity($userId, $username, $action) {
    // Database connection
    include '../includes/db.php';
    
    // Prepare SQL query to insert log entry
    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, username, action) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $username, $action);
    
    // Execute the query
    if ($stmt->execute()) {
        // Success
        return true;
    } else {
        // Error
        return false;
    }

    // Close statement
    $stmt->close();
}
?>
