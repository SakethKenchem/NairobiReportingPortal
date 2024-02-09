<?php
// Start the session
session_start();
$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'isp';

$conn = new mysqli($server, $username, $password, $dbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $nationalId = $_POST['national_id'];
    $phoneNumber = $_POST['phoneNumber'];
    $securityPhrase_digit = $_POST['security_phrase_or_digit']; // Corrected the variable name

    if (empty($username) || empty($email) || empty($password) || empty($nationalId) || empty($phoneNumber) || empty($securityPhrase_digit)) { // Corrected the condition
        echo "All fields are required.";
        exit();
    }

    // Check if the username already exists
    $check_username_sql = "SELECT * FROM userlogincredentials WHERE username = '$username'";
    $result = mysqli_query($conn, $check_username_sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username already taken. Please choose a different username.'); window.location.href='residentsignup.html';</script>";
        exit();
    }

    // Check if the email already exists
    $check_email_sql = "SELECT * FROM userlogincredentials WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email_sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already taken. Please choose a different email.'); window.location.href='residentsignup.html';</script>";
        exit();
    }

    // Check if the national ID or passport already exists
    $check_national_id_sql = "SELECT * FROM userlogincredentials WHERE national_id = '$nationalId'";
    $result = mysqli_query($conn, $check_national_id_sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('This national ID or Passport Number is already used. Please sign up with another one!'); window.location.href='residentsignup.html';</script>";
        exit();
    }

    // If phone number already exists
    $check_phone_number_sql = "SELECT * FROM userlogincredentials WHERE phoneNumber = '$phoneNumber'";
    $result = mysqli_query($conn, $check_phone_number_sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('This phone number is already used. Please sign up with another one!'); window.location.href='residentsignup.html';</script>";
        exit();
    }

    // Check if the security phrase or digit already exists
    $check_security_phrase_digit_sql = "SELECT * FROM userlogincredentials WHERE security_phrase_or_digit = '$securityPhrase_digit'"; // Corrected the column name
    $result = mysqli_query($conn, $check_security_phrase_digit_sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('This security phrase or digit is already used. Please sign up with another one!'); window.location.href='residentsignup.html';</script>";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user with account_created_at set to default (current timestamp)
    $sql = "INSERT INTO userlogincredentials (username, email, password, national_id, phoneNumber, security_phrase_or_digit) VALUES ('$username', '$email', '$hashedPassword', '$nationalId', '$phoneNumber', '$securityPhrase_digit')"; // Corrected the column name

    if (mysqli_query($conn, $sql)) {
        header("Location: residentlogin.html");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
