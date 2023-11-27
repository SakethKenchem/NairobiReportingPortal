<?php
date_default_timezone_set('Africa/Nairobi');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $targetDir = "postsImages/";
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = array("jpg", "jpeg", "png", "gif");

    if (!in_array($imageFileType, $allowedTypes)) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        exit;
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $description = $_POST["description"];
        $location = $_POST["location"];
        
        // Retrieve the username and userid from the session
        session_start();
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
        $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        date_default_timezone_set('Africa/Nairobi');
        $upload_datetime = date('Y-m-d H:i:s');

        $sql = "INSERT INTO posts (username, image_path, description, location, datecreated, userid) VALUES ('$username', '$targetFile', '$description', '$location', '$upload_datetime', '$userid')";

        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Post created successfully!"); window.location.href = "postCreate.php";</script>';
        } else {
            echo "Error: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>