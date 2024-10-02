<?php
session_start();
include 'log_activity.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php
include '../includes/db.php';
include '../includes/header.php';

// Fetch tasks from the task_sheet table
$sql = "SELECT * FROM task_sheet";
$result = $conn->query($sql);

// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error", 0);

function error_display($errno, $errstr, $errfile, $errline)
{
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL, 3, "../error/error_log.txt");
}
set_error_handler(callback: "error_display");
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

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
                <h2 class="mb-0">Task List</h2>
                <a href="../add/add_task.php" class="btn btn-primary">Add New Task</a>
            </div>

            <!-- Dashboard Stats -->
            <div class="row">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Task Name</th>
                            <th>Task Description</th>
                            <th>Start Date</th>
                            <th>Due Date</th>
                            <th>Completion Date</th>
                            <th>Task Status</th>
                            <th>Priority Level</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>{$row['task_id']}</td>
                                    <td>{$row['task_name']}</td>
                                    <td>{$row['task_description']}</td>
                                    <td>{$row['start_date']}</td>
                                    <td>{$row['due_date']}</td>
                                    <td>{$row['completion_date']}</td>
                                    <td>{$row['task_status']}</td>
                                    <td>{$row['priority_level']}</td>
                                    <td>{$row['remarks']}</td>
                                    <td>
                                        <a href='../superadmin/view_task.php?task_id={$row['task_id']}' class='btn btn-info btn-sm'>View</a>
                                        <a href='../edit/edit_task.php?task_id={$row['task_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='../delete/delete_task.php?task_id={$row['task_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete {$row['task_name']} task?\");'>Delete</a>
                                    </td>
                                  </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No tasks found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
