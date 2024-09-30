<?php

include '../includes/db.php'; // Include your database connection

$limit = $_POST['limit'] ?? 6;
$page = $_POST['page'] ?? 1;
$search_query = $_POST['search_query'] ?? '';
$sort_column = $_POST['sort_column'] ?? 'branch_id';
$sort_order = $_POST['sort_order'] ?? 'ASC';

$offset = ($page - 1) * $limit;

// Base query for fetching branches
$query = "SELECT * FROM branches WHERE 
    branch_name LIKE '%$search_query%' OR 
    branch_address LIKE '%$search_query%' OR 
    city LIKE '%$search_query%' OR 
    state LIKE '%$search_query%' OR 
    contact_number LIKE '%$search_query%' OR 
    email LIKE '%$search_query%' OR 
    branch_manager LIKE '%$search_query%' OR 
    status LIKE '%$search_query%' 
    ORDER BY $sort_column $sort_order 
    LIMIT $limit OFFSET $offset";

// Execute the query
$result = mysqli_query($conn, $query);
$branches = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $branches[] = $row;
    }
}

// Total records query
$total_query = "SELECT COUNT(*) as total FROM branches WHERE 
    branch_name LIKE '%$search_query%' OR 
    branch_address LIKE '%$search_query%' OR 
    city LIKE '%$search_query%' OR 
    state LIKE '%$search_query%' OR 
    contact_number LIKE '%$search_query%' OR 
    email LIKE '%$search_query%' OR 
    branch_manager LIKE '%$search_query%' OR 
    status LIKE '%$search_query%'";

$total_result = mysqli_query($conn, $total_query);
$total_records = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_records / $limit);

// Return data as JSON
$response = [
    'records' => $branches,
    'total_pages' => $total_pages,
];

echo json_encode($response);


// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error",0);

function error_display($errno, $errstr, $errfile, $errline){
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL,3,"../error/error_log.txt");
}
set_error_handler(callback: "error_display");
?>
