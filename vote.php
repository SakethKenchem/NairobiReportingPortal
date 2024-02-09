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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST["postid"];

    if (isset($_POST["upvote"])) {
        $conn->query("UPDATE posts SET votes = votes + 1 WHERE postid = $postId");

        $_SESSION["voted_posts"][$postId] = true;
    }

    if (isset($_POST["retract_vote"])) {
        $currentVotes = $conn->query("SELECT votes FROM posts WHERE postid = $postId")->fetch_assoc()["votes"];
        if ($currentVotes > 0) {
            // Update the vote count
            $conn->query("UPDATE posts SET votes = votes - 1 WHERE postid = $postId");

            unset($_SESSION["voted_posts"][$postId]);
        }
    }

    $conn->close();
    header("Location: homepage.php");
    exit;
}
