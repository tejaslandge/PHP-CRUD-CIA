<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}

include '../includes/db.php';
include '../includes/header.php';

// Set the number of records per page
$records_per_page = 4;

// Get the current page number from the URL, default is page 1
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $records_per_page;

// Get sorting column and direction from the URL, default is sorting by 'trainer_id' in 'ASC'
$sort_column = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'trainer_id';
$sort_direction = isset($_GET['sort_direction']) && $_GET['sort_direction'] == 'ASC' ? 'ASC' : 'DESC';

// Get search query from the URL
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM trainers WHERE CONCAT(first_name, ' ', last_name) LIKE ? 
    OR email LIKE ? 
    OR phone_number LIKE ? 
    OR trainer_branch LIKE ? 
    ORDER BY $sort_column $sort_direction LIMIT ?, ?");
$like_search_query = "%$search_query%";
$stmt->bind_param("ssssii", $like_search_query, $like_search_query, $like_search_query, $like_search_query, $offset, $records_per_page);
$stmt->execute();
$result = $stmt->get_result();

// Total record count for pagination using prepared statements
$count_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM trainers WHERE CONCAT(first_name, ' ', last_name) LIKE ? 
    OR email LIKE ? 
    OR phone_number LIKE ? 
    OR trainer_branch LIKE ?");
$count_stmt->bind_param("ssss", $like_search_query, $like_search_query, $like_search_query, $like_search_query);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Error Handling Setup
error_reporting(E_ALL);
ini_set("display_errors", 1);

function error_display($errno, $errstr, $errfile, $errline){
    $message = "Error: $errno, Error Message: $errstr, Error File: $errfile, Error Line: $errline";
    error_log($message . PHP_EOL, 3, "../error/error_log.txt");
}
set_error_handler("error_display");
?>

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
                <h1 class="h2">Trainers</h1>

                <!-- Search form -->
                <form id="searchForm" class="flex-grow-1 mx-3">
                    <div class="input-group">
                        <input type="text" id="searchQuery" name="search_query" class="form-control"
                               placeholder="Search by name, email, branch, etc." value="<?php echo htmlspecialchars($search_query); ?>">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <div class="d-flex align-items-center">
                    <a href="../csv/export_trainers.php" class="btn btn-outline-success me-2" id="exportCSV">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </a>
                    <a href="#" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#importTrainerModal">
                        <i class="fas fa-upload"></i> Import CSV
                    </a>
                    <a href="../add/add_trainer.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Trainer
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                    <tr>
                        <th scope="col"><a href="?sort_column=trainer_id&sort_direction=<?php echo $sort_direction == 'ASC' ? 'DESC' : 'ASC'; ?>">#</a></th>
                        <th scope="col">Profile</th>
                        <th scope="col"><a href="?sort_column=first_name&sort_direction=<?php echo $sort_direction == 'ASC' ? 'DESC' : 'ASC'; ?>">Trainer Name</a></th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Email</th>
                        <th scope="col">Trainer Branch</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        $srno = ($current_page - 1) * $records_per_page + 1;
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $srno . '</td>';
                            echo '<td><img src="../trainer_profile/' . htmlspecialchars($row['photo']) . '" height="100" width="100" alt="' . htmlspecialchars($row['first_name']) . '"></td>';
                            echo '<td>' . htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['last_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['phone_number']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['trainer_branch']) . '</td>';
                            echo '<td>';
                            echo $row['status'] == 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                            echo '</td>';
                            echo '<td>
                                <a href="trainer_details.php?trainer_id=' . $row['trainer_id'] . '"><button class="btn btn-warning fas fa-eye"></button></a>
                                <a href="../edit/edit_trainer.php?trainer_id=' . $row['trainer_id'] . '"><button class="btn btn-secondary fas fa-edit"></button></a>
                                <a href="../delete/delete_trainer.php?id=' . $row['trainer_id'] . '" onclick="return confirm(\'Are you sure you want to delete this trainer?\');"><button class="btn btn-danger fas fa-trash-alt"></button></a>
                              </td>';
                            echo '</tr>';
                            $srno++;
                        }
                    } else {
                        echo '<tr><td colspan="8">No trainers found.</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php
                    if ($total_pages > 1) {
                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo '<li class="page-item ' . ($i == $current_page ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </nav>
        </main>
    </div>
</div>

<!-- Import CSV Modal for Trainers -->
<div class="modal fade" id="importTrainerModal" tabindex="-1" aria-labelledby="importTrainerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="importTrainerModalLabel">Import Trainers Data from CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importTrainerCSVForm" enctype="multipart/form-data" method="POST" action="../csv/import_trainers.php">
                <div class="modal-body">
                    <div class="alert alert-info">
                        Ensure your CSV follows the correct format before uploading.
                        <br>
                        <a href="../csv/trainers.csv" class="btn btn-primary mt-2">
                            <i class="fas fa-file-download"></i> Download Sample CSV
                        </a>
                    </div>

                    <div class="form-group mt-4">
                        <label for="trainerCsvFile" class="form-label">Upload Your CSV File:</label>
                        <input type="file" class="form-control" id="trainerCsvFile" name="csvFile" accept=".csv" required>
                    </div>

                    <div class="mt-4">
                        <h6>CSV File Requirements:</h6>
                        <ul>
                            <li>First Name, Last Name, Email, Phone, Expertise, Trainer Branch, etc.</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-success">Upload and Import</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
$stmt->close();
$count_stmt->close();
mysqli_close($conn);
?>
