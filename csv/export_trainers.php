<?php
include '../includes/db.php';

// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error",0);

function error_display($errno, $errstr, $errfile, $errline){
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL,3,"../error/error_log.txt");
}
set_error_handler(callback: "error_display");


// Set the headers to download the file as CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="trainers_export.csv"');

$output = fopen('php://output', 'w');

// Write the column headers
fputcsv($output, array( 'First Name', 'Last Name', 'Email', 'Phone Number', 'Trainer Branch', 'Status'));

// Fetch and write the data rows
$sql = "SELECT  first_name, last_name, email, phone_number, trainer_branch, status FROM trainers";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit();



?>
