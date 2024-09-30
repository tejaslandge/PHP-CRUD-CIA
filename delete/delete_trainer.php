<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}

include '../includes/db.php';
include '../superadmin/log_activity.php'; // Include activity log

if (isset($_GET['id'])) {
    $trainer_id = $_GET['id'];
    $trainer_name = $_GET['first_name'];


    // SQL query to delete the trainer
    $sql = "DELETE FROM trainers WHERE trainer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $trainer_id);

    if ($stmt->execute()) {
        
        // Log activity if deletion is successful
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            logActivity($_SESSION['user_id'], $_SESSION['username'], "Deleted trainer with ID: $trainer_name");
        }
        header('Location: ../superadmin/trainers.php?msg=Trainer deleted successfully');
    } else {
        echo "Error deleting trainer: " . $stmt->error;
    }
} else {
    echo "No trainer selected.";
}
?>
