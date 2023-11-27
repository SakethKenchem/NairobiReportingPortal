<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.html");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION["username"]; 
    $post_id = $_POST["postid"]; 
    $comment_text = $_POST["comment"]; 
    
    $sql = "INSERT INTO comments (post_id, username, comment_text) VALUES ('$post_id', '$username', '$comment_text')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: homepage.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>