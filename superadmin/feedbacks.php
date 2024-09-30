<?php
session_start();
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
ini_set("Display_error",0);

function error_display($errno, $errstr, $errfile, $errline){
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL,3,"../error/error_log.txt");
}
set_error_handler(callback: "error_display");

?>

<!-- Dashboard Container -->
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav id="sidebar" class="col-md-3 d-none d-md-block col-lg-2 d-md-block bg-light fixed-top-10 ">
            <?php include '../includes/sidebar.php'; ?>
        </nav>
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div
                class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Feedbacks</h1>
            </div>

            <!-- Dashboard Stats -->
            <div class="row">
                <h1> All Feedbacks</h1>
            
            </div>
        </main>
    </div>
</div>

<?php
include '../includes/footer.php';
?>