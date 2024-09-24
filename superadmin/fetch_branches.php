<?php
include '../includes/db.php'; // Include database connection

// Initialize the query for fetching all branches or search results
$sql = "SELECT * FROM branches";

// Check if a search query was sent via POST
if (isset($_POST['search_query']) && !empty($_POST['search_query'])) {
    $search_query = $_POST['search_query'];

    // Protect against SQL injection
    $search_query = $conn->real_escape_string($search_query);

    // Modify the query to search across relevant columns
    $sql = "SELECT * FROM branches WHERE 
            branch_name LIKE '%$search_query%' OR 
            branch_address LIKE '%$search_query%' OR 
            city LIKE '%$search_query%' OR 
            state LIKE '%$search_query%' OR 
            contact_number LIKE '%$search_query%' OR 
            email LIKE '%$search_query%' OR 
            branch_manager LIKE '%$search_query%' OR 
            status LIKE '%$search_query%'";
}

// Execute the query
$result = mysqli_query($conn, $sql);

// Initialize an array to hold the branches data
$branches = [];

// Fetch all rows and append them to the array
while ($row = mysqli_fetch_assoc($result)) {
    $branches[] = $row;
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($branches);

mysqli_close($conn);
?>
