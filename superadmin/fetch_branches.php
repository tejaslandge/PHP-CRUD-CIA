<?php
include '../includes/db.php'; // Database connection

// Get the current page number
$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
$limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 6;
$offset = ($page - 1) * $limit;

// Get sorting column and order
$sort_column = isset($_POST['sort_column']) ? $_POST['sort_column'] : 'branch_id';
$sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'ASC';

// Get search query
$search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';

// Prepare the base SQL query
$sql = "SELECT * FROM branches WHERE 1=1";

// Modify the query if a search is made
if (!empty($search_query)) {
    $search_query = $conn->real_escape_string($search_query);
    $sql .= " AND (branch_name LIKE '%$search_query%' OR 
                    branch_address LIKE '%$search_query%' OR 
                    city LIKE '%$search_query%' OR 
                    state LIKE '%$search_query%' OR 
                    contact_number LIKE '%$search_query%' OR 
                    email LIKE '%$search_query%' OR 
                    branch_manager LIKE '%$search_query%' OR 
                    status LIKE '%$search_query%')";
}

// Add sorting to the query
$sql .= " ORDER BY $sort_column $sort_order LIMIT $limit OFFSET $offset";

// Execute the query
$result = mysqli_query($conn, $sql);

// Get total records count for pagination
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM branches");
$total_records = mysqli_fetch_assoc($total_result)['total'];

// Prepare the response data
$response = [];
$response['records'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
$response['total_pages'] = ceil($total_records / $limit);

// Return the response as JSON
echo json_encode($response);
?>
