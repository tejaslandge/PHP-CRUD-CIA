<?php
session_start();
include '../includes/db.php'; // Include database connection

// Number of records per page
$records_per_page = 5;

// Get the current page number from the URL or set to 1 by default
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for SQL query
$offset = ($page - 1) * $records_per_page;

// Initialize the query for fetching branches with pagination
$sql = "SELECT * FROM branches ORDER BY branch_id DESC LIMIT $records_per_page OFFSET $offset";

// Check if a search has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search_query'])) {
    $search_query = $_POST['search_query'];

    // Protect against SQL injection
    $search_query = $conn->real_escape_string($search_query);

    // Modify the query to search across relevant columns with pagination
    $sql = "SELECT * FROM branches WHERE 
            branch_name LIKE '%$search_query%' OR 
            branch_address LIKE '%$search_query%' OR 
            city LIKE '%$search_query%' OR 
            state LIKE '%$search_query%' OR 
            contact_number LIKE '%$search_query%' OR 
            email LIKE '%$search_query%' OR 
            branch_manager LIKE '%$search_query%' OR 
            status LIKE '%$search_query%' 
            ORDER BY branch_id DESC LIMIT $records_per_page OFFSET $offset";
}

// Execute the query
$result = mysqli_query($conn, $sql);

// Get the total number of records
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM branches");
$total_records = mysqli_fetch_assoc($total_result)['total'];

// Calculate total pages
$total_pages = ceil($total_records / $records_per_page);

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
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h2>Branches</h2>
                <div>
                    <a href="#" class="btn btn-outline-success mb-2 me-2" id="exportCSV">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </a>
                    <a href="#" class="btn btn-outline-primary mb-2" data-bs-toggle="modal"
                        data-bs-target="#importModal">
                        <i class="fas fa-upload"></i> Import CSV
                    </a>
                </div>
                <a href="../add/add_branch.php" class="btn btn-primary mb-2">Add New Branch</a>
            </div>


            <!-- Search form -->
            <form id="searchForm">
                <div class="form-group d-flex">
                    <input type="text" id="searchQuery" name="search_query" class="form-control"
                        placeholder="Search by branch name, city, state, etc.">
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
                <!-- Pagination -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php if ($i == $page)
                                echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>

            </div>
        </main>
    </div>
</div>
<!-- Import CSV Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importCSVForm" enctype="multipart/form-data" method="POST"
                action="../csv/import_branches.php">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csvFile" class="form-label">Choose CSV file</label>
                        <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
mysqli_close($conn);
include '../includes/footer.php';
?>

<script>

    $(document).ready(function () {
        $('#searchQuery').on('keyup', function () {
            var query = $(this).val();
            $.ajax({
                url: "../superadmin/fetch_branches.php",
                method: "POST",
                data: { search_query: query },
                success: function (data) {
                    $('#branchResults').html(data);
                }
            });
        });
    });


    document.getElementById('exportCSV').addEventListener('click', function () {
        window.location.href = "../csv/export_branches.php";
    });

</script>