<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}
?>
<?php
include '../includes/db.php';



if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] == 0) {
    $file = $_FILES['csvFile']['tmp_name'];
    


    // Open the CSV file
    $handle = fopen($file, 'r');

    // Skip the first line (header)
    fgetcsv($handle);

    // Read each row from the CSV file
    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        // Insert the data into the database
        $branch_name = $data[1];
        $branch_manager = $data[2];
        $email = $data[3];
        $contact_number = $data[4];
        $status = $data[5];

        $sql = "INSERT INTO branches (branch_name, branch_manager, email, contact_number, status) 
                VALUES ('$branch_name', '$branch_manager', '$email', '$contact_number', '$status')";

        mysqli_query($conn, $sql);
    }

    fclose($handle);
    header('Location: ../superadmin/branches.php');
}
?>
