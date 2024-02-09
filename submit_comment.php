<?php
session_start();
// Create connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: residentlogin.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST["postid"];
    $username = $_SESSION["username"];
    $comment = $_POST["comment"];

    // Insert the comment, including the `userid` field
    $userid = $_SESSION["userid"]; // Assuming you have the userid in the session

    $sql_insert_comment = "INSERT INTO comments (postid, userid, username, comment) VALUES ('$post_id', '$userid', '$username', '$comment')";

    if ($conn->query($sql_insert_comment) === TRUE) {
        // Update comment count in the `posts` table
        $sql_update_comment_count = "UPDATE posts SET comments = comments + 1 WHERE postid = '$post_id'";

        if ($conn->query($sql_update_comment_count) === TRUE) {
            header("Location: homepage.php");
            exit;
        } else {
            echo "Error updating comment count: " . $conn->error;
        }
    } else {
        echo "Error inserting comment: " . $conn->error;
    }

    $conn->close();
}
