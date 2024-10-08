
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ./superadmin/login_form.php'); // Redirect to login if not logged in
    exit;
}

// Error Handle 
error_reporting(E_ALL);
ini_set("Display_error",0);

function error_display($errno, $errstr, $errfile, $errline){
    $message = "Error : $errno ,Error Message : $errstr,Error_file:$errfile ,Error_line : $errline";
    error_log($message . PHP_EOL,3,"./error/error_log.txt");
}
set_error_handler(callback: "error_display");
?>