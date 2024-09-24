<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Number of records per page
$records_per_page = 8;

// Get the current page number from the URL or set to 1 by default
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for SQL query
$offset = ($page - 1) * $records_per_page;

// Fetch logs from the database with pagination
$sql = "SELECT * FROM activity_log ORDER BY log_time DESC LIMIT $records_per_page OFFSET $offset";
$result = $conn->query($sql);

// Get the total number of records
$total_result = $conn->query("SELECT COUNT(*) as total FROM activity_log");
$total_records = $total_result->fetch_assoc()['total'];

// Calculate total pages
$total_pages = ceil($total_records / $records_per_page);

// Including header and Bootstrap
include '../includes/header.php';
?>

<!-- Dashboard Container -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 d-none d-md-block col-lg-2 d-md-block bg-light fixed-top-10">
            <?php include '../includes/sidebar.php'; ?>
        </nav>
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">User Activity Log</h1>
            </div>

            <!-- Dashboard Stats -->
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <?php
                        if ($result->num_rows > 0) {
                            echo "<table class='table table-striped table-bordered'>";
                            echo "<thead class='table-dark'>";
                            echo "<tr><th>Log ID</th><th>User ID</th><th>Username</th><th>Action</th><th>Time</th></tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['log_id'] . "</td>";
                                echo "<td>" . $row['user_id'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['action'] . "</td>";
                                echo "<td>" . $row['log_time'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo "<p class='alert alert-info'>No activity log available.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>

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
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
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

        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
