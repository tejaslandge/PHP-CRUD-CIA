<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php

include '../includes/db.php'; // Include database connection


// Number of records per page
$records_per_page = 6;

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

$ip = $_SERVER['REMOTE_ADDR'];
// echo 'Hello'.$ip;
error_log("========IP: $ip=============");



error_reporting(E_ALL);
ini_set("Display_error",0);

function error_display($errno, $errstr, $errfile, $errline){
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL,3,"../error/error_log.txt");
}
set_error_handler(callback: "error_display");
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
        <?php if (isset($_SESSION['addbranch'])): ?>
                <?php echo $_SESSION['addbranch']; ?>
                <?php unset($_SESSION['addbranch']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['editbranch'])): ?>
                <?php echo $_SESSION['editbranch']; ?>
                <?php unset($_SESSION['editbranch']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['delbranch'])): ?>
                <?php echo $_SESSION['delbranch']; ?>
                <?php unset($_SESSION['delbranch']); ?>
            <?php endif; ?>
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
                <!-- Title Section -->
                <h2 class="mb-0">Branches</h2>

                <!-- Search form -->
                <form id="searchForm" class="flex-grow-1 mt-3 mx-3">
                    <div class="input-group">
                        <input type="text" id="searchQuery" name="search_query" class="form-control"
                            placeholder="Search by branch name, city, state, etc.">

                    </div>
                </form>

                <!-- CSV Import/Export Section -->
                <div class="d-flex align-items-center">
                    <a href="#" class="btn btn-outline-success me-2" id="exportCSV">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </a>
                    <a href="#" class="btn btn-outline-primary me-2" data-bs-toggle="modal"
                        data-bs-target="#importModal">
                        <i class="fas fa-upload"></i> Import CSV
                    </a>
                    <a href="../add/add_branch.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Branch
                    </a>
                </div>
            </div>





            <!-- Table of branches -->
            <div class="table-responsive" id="branchResults">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col"><a href="#" class="sort" data-sort="branch_id">Sr. No.</a></th>
                            <th scope="col"><a href="#" class="sort" data-sort="branch_name">Branch Name</a></th>
                            <th scope="col"><a href="#" class="sort" data-sort="branch_manager">Branch Manager</a></th>
                            <th scope="col"><a href="#" class="sort" data-sort="email">Branch Email</a></th>
                            <th scope="col"><a href="#" class="sort" data-sort="contact_number">Branch Mobile</a></th>
                            <th scope="col"><a href="#" class="sort" data-sort="status">Status</a></th>
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
                                    <a href="../delete/delete_branch.php?id=' . $row['branch_id'] . '" onclick="return confirm(\'Are you sure you want to delete this ' . $row['branch_name'] . '?\');">
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importModalLabel">Import Branches Data from CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importCSVForm" enctype="multipart/form-data" method="POST" action="../csv/import_branches.php">
                <div class="modal-body">
                    <div class="alert alert-info">
                        Ensure your CSV follows the correct format before uploading.
                        <br>
                        <a href="../csv/branches.csv" class="btn btn-primary mt-2">
                            <i class="fas fa-file-download"></i> Download Sample CSV
                        </a>
                    </div>

                    <div class="form-group mt-4">
                        <label for="csvFile" class="form-label">Upload Your CSV File:</label>
                        <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
                    </div>

                    <div class="mt-4">
                        <h6>Expected CSV Format:</h6>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>branch_name:</strong> Name of the branch</li>
                            <li class="list-group-item"><strong>branch_manager:</strong> Name of the manager</li>
                            <li class="list-group-item"><strong>email:</strong> Branch email address</li>
                            <li class="list-group-item"><strong>contact_number:</strong> Contact number of the branch</li>
                            <li class="list-group-item"><strong>status:</strong> Active or Inactive</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload CSV</button>
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
    // Variables to track sorting order and column
    var sortColumn = 'branch_id'; // Default sort by branch_id
    var sortOrder = 'DESC'; // Default sort order

    // Function to load branches with pagination and sorting
    function loadBranches(page = 1, query = '') {
        $.ajax({
            url: "../superadmin/fetch_branches.php",
            method: "POST",
            data: {
                search_query: query,
                page: page,
                limit: 6,
                sort_column: sortColumn,
                sort_order: sortOrder
            },
            dataType: 'json', // Expect JSON response
            success: function (data) {
                // Clear the existing table body
                var tbody = $('#branchResults tbody');
                tbody.empty();

                // Check if data is not empty
                if (data.records.length > 0) {
                    $.each(data.records, function (index, row) {
                        var statusBadge = row.status === 'active' ?
                            '<span class="badge bg-success">Active</span>' :
                            '<span class="badge bg-danger">Inactive</span>';

                        tbody.append(`
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${row.branch_name}</td>
                                <td>${row.branch_manager}</td>
                                <td>${row.email}</td>
                                <td>${row.contact_number}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <a href="branch_details.php?id=${row.branch_id}">
                                        <button class="btn btn-warning fas fa-eye"></button>
                                    </a>
                                    <a href="../edit/edit_branch.php?id=${row.branch_id}">
                                        <button class="btn btn-secondary fas fa-edit"></button>
                                    </a>
                                    <a href="../delete/delete_branch.php?id=${row.branch_id}" onclick="return confirm('Are you sure you want to delete this ${row.branch_name}?');">
                                        <button class="btn btn-danger fas fa-trash-alt"></button>
                                    </a>
                                </td>
                            </tr>
                        `);
                    });

                    // Update pagination
                    var pagination = $('.pagination');
                    pagination.empty();

                    if (data.total_pages > 1) {
                        if (page > 1) {
                            pagination.append(`
                                <li class="page-item">
                                    <a class="page-link" href="#" data-page="${page - 1}">&laquo;</a>
                                </li>
                            `);
                        }
                        for (var i = 1; i <= data.total_pages; i++) {
                            pagination.append(`
                                <li class="page-item ${i === page ? 'active' : ''}">
                                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                                </li>
                            `);
                        }
                        if (page < data.total_pages) {
                            pagination.append(`
                                <li class="page-item">
                                    <a class="page-link" href="#" data-page="${page + 1}">&raquo;</a>
                                </li>
                            `);
                        }
                    }
                } else {
                    tbody.append('<tr><td colspan="7">No branches found.</td></tr>');
                }
            }
        });
    }

    // On page load, load the first page
    loadBranches();

    // Search function
    $('#searchQuery').on('keyup', function () {
        var query = $(this).val();
        loadBranches(1, query); // Load first page of search results
    });

    // Handle sorting
    $('.sort').on('click', function (e) {
        e.preventDefault();
        var column = $(this).data('sort');
        if (sortColumn === column) {
            // Toggle sort order if the same column is clicked
            sortOrder = (sortOrder === 'ASC') ? 'DESC' : 'ASC';
        } else {
            // Sort by new column
            sortColumn = column;
            sortOrder = 'DESC';
        }
        loadBranches(1, $('#searchQuery').val()); // Load first page with new sorting
    });

    // Handle pagination click
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        var page = $(this).data('page');
        var query = $('#searchQuery').val();
        loadBranches(page, query);
    });
});

    document.getElementById('exportCSV').addEventListener('click', function () {
        var query = $('#searchQuery').val();
        var exportUrl = "../csv/export_branches.php";

        // Append the search query if it exists
        if (query) {
            exportUrl += "?search_query=" + encodeURIComponent(query);
        }
        window.location.href = exportUrl;
    });




</script>