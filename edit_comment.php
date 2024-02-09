<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $commentId = $_POST["commentId"];
    $newComment = $_POST["newComment"];




    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "isp";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $newComment = $conn->real_escape_string($newComment);
    $conn->query("UPDATE comments SET comment = '$newComment' WHERE comment_id = $commentId");

    $response = [
        "success" => true,
        "username" => $_SESSION["username"],
        "comment" => $newComment
    ];

    echo json_encode($response);

    $conn->close();
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
