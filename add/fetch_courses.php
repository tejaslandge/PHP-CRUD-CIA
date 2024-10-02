<?php
include '../includes/db.php'; // Include your database connection file

// Fetch course names from the database
$query = "SELECT course_name FROM courses";
$result = mysqli_query($conn, $query);

$courses = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row['course_name'];
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($courses);
?>
