<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$_SESSION["username"] = $username;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT userid, username, password, is_blocked FROM userlogincredentials WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if ($row["is_blocked"] == 1) {
            echo "<script>alert('User is blocked due to policy violations.'); window.location.href='residentlogin.html';</script>";
            exit();
        }

        if (password_verify($password, $row["password"])) {
            $_SESSION['userid'] = $row["userid"];
            $_SESSION['username'] = $row["username"];
            $_SESSION['loggedin'] = true;

            header("Location: homepage.php");
            exit();
        } else {
            echo "<script>alert('Invalid username or password'); window.location.href='residentlogin.html';</script>";
        }
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href='residentlogin.html';</script>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $nationalId = $_POST["national_id"];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $check_username_sql = "SELECT * FROM userlogincredentials WHERE username = '$username'";
    $result = $conn->query($check_username_sql);

    if ($result->num_rows > 0) {
        echo "<script>alert('Username already taken. Please choose a different username.'); window.location.href='residentlogin.html';</script>";
        exit();
    }

    $check_email_sql = "SELECT * FROM userlogincredentials WHERE email = '$email'";
    $result = $conn->query($check_email_sql);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already taken. Please choose a different email.'); window.location.href='residentlogin.html';</script>";
        exit();
    }

    $check_national_id_sql = "SELECT * FROM userlogincredentials WHERE national_id = '$nationalId'";
    $result = $conn->query($check_national_id_sql);

    if ($result->num_rows > 0) {
        echo "<script>alert('This national ID or Passport Number is already used. Please sign up with another one!'); window.location.href='residentlogin.html';</script>";
        exit();
    }

    $sql = "INSERT INTO userlogincredentials (username, email, password, national_id) VALUES ('$username', '$email', '$hashedPassword', '$nationalId')";

    if ($conn->query($sql) === TRUE) {
        header("Location: residentlogin.html");
        exit();
    } else {
        $signupError = "Error: " . $conn->error;
    }
}
?>
