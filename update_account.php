<?php
session_start();

// Establish a database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'isp';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "You are not logged in. Please log in first.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentUsername = $_POST['current_username'];
    $newUsername = $_POST['new_username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $national_id = $_POST['national_id'];


    $checkQuery = "SELECT * FROM userlogincredentials WHERE username='$currentUsername'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows === 1) {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $updateQuery = "UPDATE userlogincredentials SET username='$newUsername', email='$email', password='$hashedPassword', national_id='$national_id' WHERE username='$currentUsername'";
        $updateResult = $conn->query($updateQuery);

        if ($updateResult === true) {
            echo '<script>alert("Account Details updated successfully!"); window.location.href = "edit_account.php";</script>';
        } else {
            echo "Failed to update account information.";
        }
    } else {
        echo "Invalid current username. Please try again.";
    }
}

$conn->close();
