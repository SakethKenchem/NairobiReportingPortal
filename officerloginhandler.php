<?php
session_name("officer_session");
session_start();
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'isp';

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Error: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM officer_credentials WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $row['id'];
            
            header("Location: officerDashboard.php");
            exit();
        } else {
            echo "Incorrect password"; 
            header("Location: officerlogin.html");
        }
    } else {
        echo "Username not found";
        header("Location: officerlogin.html");
    }
}
?>
