<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}

// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error",0);

function error_display($errno, $errstr, $errfile, $errline){
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL,3,"../error/error_log.txt");
}
set_error_handler(callback: "error_display");

include '../includes/db.php'; // Assuming db.php handles the database connection
include '../superadmin/log_activity.php';

if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == 0) {
    $file = $_FILES['csvFile']['tmp_name'];

    // Open the CSV file
    if (($handle = fopen($file, 'r')) !== FALSE) {
        // Skip the first line (header)
        fgetcsv($handle);

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO branches (branch_name, branch_manager, email, contact_number, status) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error preparing the statement: " . $conn->error);
        }

        // Loop through each row in the CSV
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            // Sanitize and validate data
            $branch_name = trim($data[0]);
            $branch_manager = trim($data[1]);
            $email = filter_var($data[2], FILTER_VALIDATE_EMAIL) ? $data[2] : null;
            $contact_number = preg_match('/^[0-9]{10}$/', $data[3]) ? $data[3] : null;
            $status = in_array(strtolower($data[4]), ['active', 'inactive']) ? strtolower($data[4]) : 'inactive';

            // Check if email and contact_number are valid
            if ($email && $contact_number) {
                // Bind parameters and execute the statement
                if ($stmt->bind_param('sssss', $branch_name, $branch_manager, $email, $contact_number, $status)) {
                    $stmt->execute();
                } else {
                    echo "Error binding parameters: " . $stmt->error;
                }
            } else {
                echo "Invalid data for branch: $branch_name. Email or contact number is incorrect.<br>";
            }
        }

        // Close file and statement
        fclose($handle);
        $stmt->close();

        // Log activity after successful import
        logActivity($_SESSION['user_id'], $_SESSION['username'], "Imported CSV file with branches data.");

        // Redirect to the branches page
        header('Location: ../superadmin/branches.php');
    } else {
        echo "Error opening the CSV file.";
    }
} else {
    echo "Error uploading the file.";
}
?>
