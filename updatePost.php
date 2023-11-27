<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">    
    <style>
        .alert {
            max-width: 500px;
            margin-top: 20px;
            /*center the alert*/
            margin-left: auto;
            margin-right: auto;
        }
        .btn{
            margin-top: 10px;
        }
    </style>
    <title>Update Post</title>
</head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['postid']) && isset($_POST['description']) && isset($_POST['location'])) {
        $postid = $_POST['postid'];
        $description = $_POST['description'];
        $location = $_POST['location'];

        // Validate and sanitize the data (you should implement proper validation)
        $description = htmlspecialchars($description);
        $location = htmlspecialchars($location);

        // Database connection details
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "isp";

        // Create a database connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "uploads/"; // Directory to store uploaded files
            $uploadedFile = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($uploadedFile, $targetPath)) {
                // Update the image path in the database
                $sql = "UPDATE posts SET description = '$description', location = '$location', image_path = '$targetPath' WHERE postid = '$postid'";

                if ($conn->query($sql) === TRUE) {
                    // The post was updated successfully
                    header("Location: myposts.php");
                    exit();
                } else {
                    echo "Error updating post: " . $conn->error;
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            // If no new image was uploaded, update only description and location
            $sql = "UPDATE posts SET description = '$description', location = '$location' WHERE postid = '$postid'";

            if ($conn->query($sql) === TRUE) {
                // The post was updated successfully
                header("Location: myposts.php");
                exit();
            } else {
                echo "Error updating post: " . $conn->error;
            }
        }

        // Close the database connection
        $conn->close();
    } else {
        echo "Invalid data received from the form.";
    }
} else {
    echo "Invalid request. Please submit the form.";
}
?>

</body>
</html>
