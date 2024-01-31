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
    $update_logout_sql = "UPDATE userloginhistory SET logout_time = '$logout_time' WHERE userid = $userid AND logout_time IS NULL";
    mysqli_query($conn, $update_logout_sql);
}

session_unset();
session_destroy();

// Adding a Bootstrap loading button spinner with a delay of 2 seconds
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Logging Out</title>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Logging out...</span>
        </div>
        <div class="ml-2">Logging out...</div>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = "residentlogin.html";
        }, 2000); // 2000 milliseconds (2 seconds) delay
    </script>
</body>
</html>';
exit;
?>
