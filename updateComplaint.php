<?php
session_start();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    echo '<div class="alert alert-danger mt-4">Please log in to edit complaints.</div>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "isp";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Sanitize and retrieve form data
    $complaintId = $_POST['complaintId'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $issue_type = $_POST['issue_type'];
    $issue = $_POST['issue'];

    // Update the complaint in the database
    $sql = "UPDATE complaints SET email = '$email', phone = '$phone', address = '$address', city = '$city', issue_type = '$issue_type', issue = '$issue' WHERE complaint_id = '$complaintId'";

    if ($conn->query($sql) === TRUE) {
        // Successfully updated complaint
        header("Location: mycomplaints.php");
        exit();
    } else {
        echo '<div class="alert alert-danger mt-4">Error updating complaint: ' . $conn->error . '</div>';
    }

    $conn->close();
} else {
    echo '<div class="alert alert-danger mt-4">Invalid request method.</div>';
}
?>
