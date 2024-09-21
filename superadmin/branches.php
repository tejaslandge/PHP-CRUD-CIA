<?php
session_start();


include '../includes/db.php'; // Include database connection

// Initialize the query for fetching all branches or search results
$sql = "SELECT * FROM branches ORDER BY branch_id DESC";

// Check if a search has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search_query'])) {
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

?>

<div style="position: sticky; top:0;">
    <?php include '../includes/header.php'; ?>
</div>

<!-- Dashboard Container -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar position-sticky" style="top: 0;">
            <?php include '../includes/sidebar.php'; ?>
        </nav>

        <!-- Main content -->
        <main class="col-12 col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Branches</h1>
                <a href="../add/add_branch.php" class="btn btn-primary mb-2">Add New Branch</a>
            </div>

            <!-- Search form -->
            <form id="searchForm">
    <div class="form-group d-flex">
        <input type="text" id="searchQuery" name="search_query" class="form-control" placeholder="Search by branch name, city, state, etc.">
    </div>
</form>

            <!-- Table of branches -->
            <div class="table-responsive" id="branchResults">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Sr. No.</th>
                            <th scope="col">Branch Name</th>
                            <th scope="col">Branch Manager</th>
                            <th scope="col">Branch Email</th>
                            <th scope="col">Branch Mobile</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if there are any results
                        if (mysqli_num_rows($result) > 0) {
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
                        } else {
                            echo '<tr><td colspan="7">No branches found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php
mysqli_close($conn);
include '../includes/footer.php';
?>

<script>
$(document).ready(function() {
    // Trigger search as the user types in the search box
    $('#searchQuery').on('keyup', function() {
        var query = $(this).val();
        $.ajax({
            url: "../superadmin/fetch_branches.php", // Separate PHP script for fetching branch data
            method: "POST",
            data: { search_query: query },
            success: function(data) {
                // Update the table with the search results
                $('#branchResults').html(data);
            }
        });
    });
});
</script>
