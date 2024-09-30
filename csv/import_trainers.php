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
    error_log($message . PHP_EOL, 3, "../error/error_log.txt");
}
set_error_handler("error_display");

include '../includes/db.php'; // Assuming db.php handles the database connection
include '../superadmin/log_activity.php';

if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == 0) {
    $file = $_FILES['csvFile']['tmp_name'];

    // Open the CSV file
    if (($handle = fopen($file, 'r')) !== FALSE) {
        // Skip the first line (header)
        fgetcsv($handle);

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO trainers (first_name, last_name, email, phone_number, trainer_branch, status) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error preparing the statement: " . $conn->error);
        }

        // Loop through each row in the CSV
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            // Sanitize and validate data
            $first_name = trim($data[0]);
            $last_name = trim($data[1]);
            $email = filter_var($data[2], FILTER_VALIDATE_EMAIL) ? $data[2] : null;
            $phone_number = preg_match('/^[0-9]{10}$/', $data[3]) ? $data[3] : null;
            $trainer_branch = trim($data[4]);
            $status = in_array(strtolower($data[5]), ['active', 'inactive']) ? strtolower($data[5]) : 'inactive';

            // Check if email and phone_number are valid
            if ($email && $phone_number) {
                // Bind parameters and execute the statement
                if ($stmt->bind_param('ssssss', $first_name, $last_name, $email, $phone_number, $trainer_branch, $status)) {
                    $stmt->execute();
                } else {
                    echo "Error binding parameters: " . $stmt->error;
                }
            } else {
                echo "Invalid data for trainer: $first_name $last_name. Email or phone number is incorrect.<br>";
            }
        }

        // Close file and statement
        fclose($handle);
        $stmt->close();

        // Log activity after successful import
        logActivity($_SESSION['user_id'], $_SESSION['username'], "Imported CSV file with trainers data.");

        // Redirect to the trainers page
        header('Location: ../superadmin/trainers.php');
        exit;
    } else {
        echo "Error opening the CSV file.";
    }
} else {
    echo "Error uploading the file.";
}
?>
