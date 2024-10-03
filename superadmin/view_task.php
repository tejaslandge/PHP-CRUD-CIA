<?php
include '../includes/db.php';
include '../includes/header.php'; // Include header for Bootstrap CSS

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    $sql = "SELECT * FROM task_sheet WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $task_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Task not found.</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>Invalid request.</div>";
    exit;
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 d-none d-md-block col-lg-2 d-md-block bg-light fixed-top-10">
            <?php include '../includes/sidebar.php'; ?>
        </nav>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">Task Details</h2>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="task_name" class="form-label"><strong>Task Name:</strong></label>
                    <p><?php echo $task['task_name']; ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="task_description" class="form-label"><strong>Task Description:</strong></label>
                    <p><?php echo $task['task_description']; ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label"><strong>Start Date:</strong></label>
                    <p><?php echo $task['start_date']; ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="due_date" class="form-label"><strong>Due Date:</strong></label>
                    <p><?php echo $task['due_date']; ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="completion_date" class="form-label"><strong>Completion Date:</strong></label>
                    <p><?php echo $task['completion_date']; ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="task_status" class="form-label"><strong>Status:</strong></label>
                    <p><?php echo $task['task_status']; ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="priority_level" class="form-label"><strong>Priority Level:</strong></label>
                    <p><?php echo $task['priority_level']; ?></p>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="remarks" class="form-label"><strong>Remarks:</strong></label>
                    <p><?php echo $task['remarks']; ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <a href="../superadmin/task_sheet.php" class="btn btn-primary">Back to Task List</a>
                </div>
            </div>
        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
