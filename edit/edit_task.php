<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';
include '../superadmin/log_activity.php';


if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $task_name = $_POST['task_name'];
        $task_description = $_POST['task_description'];
        $start_date = $_POST['start_date'];
        $due_date = $_POST['due_date'];
        $completion_date = $_POST['completion_date'];
        $task_status = $_POST['task_status'];
        $priority_level = $_POST['priority_level'];
        $remarks = $_POST['remarks'];

        // Update the task
        $sql = "UPDATE task_sheet SET task_name=?, task_description=?, start_date=?, due_date=?, completion_date=?, task_status=?, priority_level=?, remarks=? WHERE task_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssssi', $task_name, $task_description, $start_date, $due_date, $completion_date, $task_status, $priority_level, $remarks, $task_id);

        if ($stmt->execute()) {
            if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
                logActivity($_SESSION['user_id'], $_SESSION['username'], "Update Task: $task_name");
            }
        $_SESSION['edittask']= "<div class='alert alert-success'>Updated Task Successfully!</div>";

            header("Location:../superadmin/task_sheet.php");
            exit;
        } else {
        $_SESSION['edittask']= "<div class='alert alert-danger'>Error updating task.</div>";
        }
    } else {
        $sql = "SELECT * FROM task_sheet WHERE task_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $task_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $task = $result->fetch_assoc();
        }
    }
}
?>
<!-- HTML form for editing the task -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 d-none d-md-block col-lg-2 d-md-block bg-light fixed-top-10 ">
            <?php include '../includes/sidebar.php'; ?>
        </nav>
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
           
            <h2 class="mt-0">Edit Task</h2>

            <form method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="task_name" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="task_name" name="task_name" value="<?php echo $task['task_name']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="task_description" class="form-label">Task Description</label>
                        <textarea class="form-control" id="task_description" name="task_description" rows="3" required><?php echo $task['task_description']; ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $task['start_date']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo $task['due_date']; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="completion_date" class="form-label">Completion Date</label>
                        <input type="date" class="form-control" id="completion_date" name="completion_date" value="<?php echo $task['completion_date']; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="task_status" class="form-label">Task Status</label>
                        <select class="form-select" id="task_status" name="task_status" required>
                            <option value="Not Started" <?php if($task['task_status'] == 'Not Started') echo 'selected'; ?>>Not Started</option>
                            <option value="In Progress" <?php if($task['task_status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                            <option value="Completed" <?php if($task['task_status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="priority_level" class="form-label">Priority Level</label>
                        <select class="form-select" id="priority_level" name="priority_level" required>
                            <option value="Low" <?php if($task['priority_level'] == 'Low') echo 'selected'; ?>>Low</option>
                            <option value="Medium" <?php if($task['priority_level'] == 'Medium') echo 'selected'; ?>>Medium</option>
                            <option value="High" <?php if($task['priority_level'] == 'High') echo 'selected'; ?>>High</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="2"><?php echo $task['remarks']; ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>

