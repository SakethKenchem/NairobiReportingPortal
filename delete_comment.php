<?php
session_start();


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {

    echo json_encode(["success" => false, "error" => "User not logged in"]);
    exit;
}


if (!isset($_POST["commentId"])) {
    // Handle the case when the comment ID is not provided
    echo json_encode(["success" => false, "error" => "Comment ID not provided"]);
    exit;
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {

    echo json_encode(["success" => false, "error" => "Database connection failed"]);
    exit;
}


$commentId = (int)$_POST["commentId"];

//prepared statement to avoid SQL injection
$stmt = $conn->prepare("SELECT username FROM comments WHERE comment_id = ?");
$stmt->bind_param("i", $commentId);
$stmt->execute();
$stmt->bind_result($commentUsername);
$stmt->fetch();
$stmt->close();

// Check if the comment exists and if the current user is the owner
if ($commentUsername === $_SESSION["username"]) {
    $stmt = $conn->prepare("DELETE FROM comments WHERE comment_id = ?");
    $stmt->bind_param("i", $commentId);

    if ($stmt->execute()) {

        echo json_encode(["success" => true]);
    } else {

        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
} else {

    echo json_encode(["success" => false, "error" => "Unauthorized to delete this comment"]);
}

$conn->close();
