<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];

    // Update logout time and set manual_logout based on parameter
    $logout_time = date("Y-m-d H:i:s"); // Current timestamp
    $manual_logout = isset($_POST['manual_logout']) ? $_POST['manual_logout'] : 1;
    $update_logout_sql = "UPDATE userloginhistory SET logout_time = '$logout_time', manual_logout = $manual_logout WHERE userid = $userid AND logout_time IS NULL";
    mysqli_query($conn, $update_logout_sql);
}

session_unset();
session_destroy();
header("Location: residentlogin.html");
exit;
?>
