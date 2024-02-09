<?php
date_default_timezone_set('Africa/Nairobi');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $targetDir = "postsImages/";
    $allowedTypes = array("jpg", "jpeg", "png", "gif");

    // Initialize an array to store the file paths of the uploaded images
    $uploadedImagePaths = array();

    // Loop through each uploaded file
    foreach ($_FILES["images"]["name"] as $key => $fileName) {
        $targetFile = $targetDir . basename($fileName);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file type is allowed
        if (!in_array($imageFileType, $allowedTypes)) {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            exit;
        }

        // Move the file to the specified directory
        if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $targetFile)) {
            $uploadedImagePaths[] = $targetFile;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    // Retrieve other form data
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

    // Insert post data into 'posts' table
    $sql = "INSERT INTO posts (username, description, location, datecreated, userid) VALUES ('$username', '$description', '$location', '$upload_datetime', '$userid')";

    if ($conn->query($sql) === TRUE) {
        // Retrieve the postid of the newly inserted post
        $postid = $conn->insert_id;

        // Insert image paths into 'post_images' table
        foreach ($uploadedImagePaths as $imagePath) {
            $sqlImage = "INSERT INTO post_images (file_path, post_id) VALUES ('$imagePath', '$postid')";
            if ($conn->query($sqlImage) !== TRUE) {
                echo "Error inserting image path: " . $conn->error;
                exit;
            }
        }

        echo '<script>alert("Post created successfully!"); window.location.href = "postCreate.php";</script>';
    } else {
        echo "Error inserting post data: " . $conn->error;
    }

    $conn->close();
}
