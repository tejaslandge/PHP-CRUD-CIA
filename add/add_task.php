<?php
session_start();
include '../superadmin/log_activity.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php
include '../includes/db.php';
include '../includes/header.php';


// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error", 0);

function error_display($errno, $errstr, $errfile, $errline)
{
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL, 3, "../error/error_log.txt");
}
set_error_handler(callback: "error_display");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_name = $_POST['task_name'];
    $task_description = $_POST['task_description'];
    $start_date = $_POST['start_date'];
    $due_date = $_POST['due_date'];
    $completion_date = $_POST['completion_date'];
    $task_status = $_POST['task_status'];
    $priority_level = $_POST['priority_level'];
    $remarks = $_POST['remarks'];

    // Insert the new task into the database
    $sql = "INSERT INTO task_sheet (task_name, task_description, start_date, due_date, completion_date, task_status, priority_level, remarks) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss', $task_name, $task_description, $start_date, $due_date, $completion_date, $task_status, $priority_level, $remarks);

    if ($stmt->execute()) {
        if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
            logActivity($_SESSION['user_id'], $_SESSION['username'], "Added a new Task : $task_name");
        }
        $_SESSION['addtask']= "<div class='alert alert-success'>Task added successfully!</div>";

        // header('location:../superadmin/task_sheet.php');
        echo "<script>window.location.href='../superadmin/task_sheet.php'</script>";
    } else {
        $_SESSION['addtask']= "<div class='alert alert-danger'>Error adding task: " . $stmt->error . "</div>";
    }
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 d-none d-md-block col-lg-2 d-md-block bg-light fixed-top-10 ">
            <?php include '../includes/sidebar.php'; ?>
        </nav>
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            
            <h2 class="mt-0">Add New Task</h2>
            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="task_name" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="task_name" name="task_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="task_description" class="form-label">Task Description</label>
                        <textarea class="form-control" id="task_description" name="task_description" rows="3" required></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="completion_date" class="form-label">Completion Date</label>
                        <input type="date" class="form-control" id="completion_date" name="completion_date">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="task_status" class="form-label">Task Status</label>
                        <select class="form-select" id="task_status" name="task_status" required>
                            <option value="Not Started">Not Started</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="priority_level" class="form-label">Priority Level</label>
                        <select class="form-select" id="priority_level" name="priority_level" required>
                            <option value="Low">Low</option>
                            <option value="Medium">Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="2"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary ">Add Task</button>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
