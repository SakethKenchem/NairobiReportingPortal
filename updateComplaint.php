<?php
session_start();

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    echo '<div class="alert alert-danger mt-4">Please log in to edit complaints.</div>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "isp";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $complaintId = $_POST['complaintId'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $issue_type = $_POST['issue_type'];
    $issue = $_POST['issue'];

    // Fetch existing images from the hidden input
    $existingImages = $_POST['existingImages'];

    $sql = "UPDATE complaints SET email = '$email', phone = '$phone', address = '$address', city = '$city', issue_type = '$issue_type', issue = '$issue'";

    // Check if new images are being uploaded
    if (!empty($_FILES['images']['name'][0])) {
        $targetDir = "uploads/";
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
        $images = $_FILES['images']['name'];
        $targetFiles = [];

        foreach ($images as $key => $image) {
            $targetFile = $targetDir . basename($image);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (in_array($imageFileType, $allowTypes)) {
                move_uploaded_file($_FILES['images']['tmp_name'][$key], $targetFile);
                $targetFiles[] = $targetFile;
            }
        }

        // Combine existing and new image paths
        $existingImagesArray = explode(',', $existingImages);
        $targetFiles = array_merge($existingImagesArray, $targetFiles);

        // Convert the array of image paths into a comma-separated string
        $imagePaths = implode(',', $targetFiles);
        // Add image paths to the update query
        $sql .= ", image_path = '$imagePaths'";
    }

    $sql .= " WHERE complaint_id = '$complaintId'";

    if ($conn->query($sql) === TRUE) {
        header("Location: mycomplaints.php");
        exit();
    } else {
        echo '<div class="alert alert-danger mt-4">Error updating complaint: ' . $conn->error . '</div>';
    }

    $conn->close();
} else {
    echo '<div class="alert alert-danger mt-4">Invalid request method.</div>';
}
