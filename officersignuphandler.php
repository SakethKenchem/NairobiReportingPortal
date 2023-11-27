<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'isp';

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Error: " . mysqli_connect_error());
}

// Handle signup with hashed password
if($_SERVER['REQUEST_METHOD']=='POST'){
    $username = $_POST['username'];
    $phonenumber = $_POST['phonenumber'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO officer_credentials (username, phonenumber, password) VALUES ('$username', '$phonenumber', '$hashed_password')";
    $result = mysqli_query($conn, $sql);
    
    if($result){
        echo "Signup successful";
        header("Location: officerlogin.html");
    }else{
        echo("Signup failed");
        header("Location: officersignup.html");
    }

}
?>
