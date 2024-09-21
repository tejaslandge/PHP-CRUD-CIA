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

// Check if there are any results
if (mysqli_num_rows($result) > 0) {
    echo '<table class="table table-striped table-bordered">';
    echo '<thead class="table-dark">';
    echo '<tr>
            <th scope="col">Sr. No.</th>
            <th scope="col">Branch Name</th>
            <th scope="col">Branch Manager</th>
            <th scope="col">Branch Email</th>
            <th scope="col">Branch Mobile</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
          </tr>';
    echo '</thead>';
    echo '<tbody>';
    
    $srno = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<th scope="row">' . $srno . '</th>';
        echo '<td>' . $row['branch_name'] . '</td>';
        echo '<td>' . $row['branch_manager'] . '</td>';
        echo '<td>' . $row['email'] . '</td>';
        echo '<td>' . $row['contact_number'] . '</td>';
        echo '<td>';
        if ($row['status'] == 'active') {
            echo '<span class="badge bg-success">Active</span>';
        } else {
            echo '<span class="badge bg-danger">Inactive</span>';
        }
        echo '</td>';
        echo '<td>
                <a href="branch_details.php?id=' . $row['branch_id'] . '"><button class="btn btn-warning fas fa-eye"></button></a>
                <a href="../edit/edit_branch.php?id=' . $row['branch_id'] . '">
                    <button class="btn btn-secondary fas fa-edit"></button>
                </a>
                <a href="../delete/delete_branch.php?id=' . $row['branch_id'] . '" onclick="return confirm(\'Are you sure you want to delete this branch?\');">
                    <button class="btn btn-danger fas fa-trash-alt"></button>
                </a>
              </td>';
        echo '</tr>';
        $srno++;
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo '<tr><td colspan="7">No branches found.</td></tr>';
}

mysqli_close($conn);
?>
