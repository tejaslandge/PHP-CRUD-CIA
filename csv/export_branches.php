<?php
include '../includes/db.php';
include '../superadmin/log_activity.php';


// Initialize the query for fetching branches
$sql = "SELECT * FROM branches";

// Check if a search query has been submitted
if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
    $search_query = $_GET['search_query'];
    // Protect against SQL injection
    $search_query = $conn->real_escape_string($search_query);


    // Modify the query to search across relevant columns
    $sql .= " WHERE 
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

// Check for query success
if ($result) {
    // Prepare CSV headers
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="branches.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Write CSV headers
    fputcsv($output, ['Branch ID', 'Branch Name', 'Branch Manager', 'Email', 'Contact Number', 'Status']);

    // Fetch and write each row to the CSV
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [$row['branch_id'], $row['branch_name'], $row['branch_manager'], $row['email'], $row['contact_number'], $row['status']]);
    }

    logActivity($_SESSION['user_id'], $_SESSION['username'], "Export CSV");

    fclose($output);
    exit; // Stop further execution
} else {
    // Handle query failure
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
