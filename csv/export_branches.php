<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php
include '../includes/db.php'; 

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=branches.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Output column headings
fputcsv($output, array('Branch ID', 'Branch Name', 'Branch Manager', 'Email', 'Contact Number', 'Status'));

// Fetch the data
$sql = "SELECT branch_id, branch_name, branch_manager, email, contact_number, status FROM branches";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

// Close the database connection
fclose($output);
exit();
?>
