<?php
// for redirecting to login page after logout in complaintform.html
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if (isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];

    // Update logout time in userloginhistory table
    $logout_time = date("Y-m-d H:i:s"); // Current timestamp
    $update_logout_time_sql = "UPDATE userloginhistory SET logout_time = '$logout_time' WHERE userid = $userid AND logout_time IS NULL";
    mysqli_query($conn, $update_logout_time_sql);
}

session_unset();
session_destroy();
header("Location: residentlogin.html");
exit;
?>
