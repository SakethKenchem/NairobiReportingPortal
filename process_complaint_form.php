<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST["userid"]; 
    $name = $_POST["name"];
    $email = $_POST["email"];
    $id_passport = $_POST["ID_Passport"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $issue = $_POST["issue"];
    $sub_issues = $_POST["sub_issues"];
    $otherIssueDiv = $_POST["if_choice_is_other"];
    $issue_type = $_POST["issue_type"];

    // Upload images codeblock
    $targetDir = "uploads/"; // folder to store the uploaded images
    $uploadedFiles = [];
    $uploadStatus = true;

    if (!empty($_FILES['images']['name'][0])) {
        if (is_array($_FILES['images']['name'])) {
            foreach ($_FILES['images']['name'] as $key => $name) {
                $targetFilePath = $targetDir . basename($name);
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                // Check if the file size is within the allowed limit (10MB)
                if ($_FILES['images']['size'][$key] > 10 * 1024 * 1024) {
                    echo '<script>alert("File size exceeds the maximum allowed size of 10MB."); window.location.href = "complaintForm.php";</script>';
                    $uploadStatus = false;
                    break;
                }

                if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $targetFilePath)) {
                    $uploadedFiles[] = $targetFilePath;
                } else {
                    echo "Error uploading file(s).";
                    $uploadStatus = false;
                    break;
                }
            }
        } else {
            $targetFilePath = $targetDir . basename($_FILES["images"]["name"]);
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Check if the file size is within the allowed limit (10MB)
            if ($_FILES['images']['size'] > 10 * 1024 * 1024) {
                echo '<script>alert("File size exceeds the maximum allowed size of 10MB."); window.location.href = "complaintForm.php";</script>';
                $uploadStatus = false;
            } elseif (move_uploaded_file($_FILES["images"]["tmp_name"], $targetFilePath)) {
                $uploadedFiles[] = $targetFilePath;
            } else {
                echo "Error uploading file.";
                $uploadStatus = false;
            }
        }
    }

    if ($uploadStatus) {
        date_default_timezone_set('Africa/Nairobi');
        $currentDateTime = date('Y-m-d H:i:s'); 
        $sql = "INSERT INTO complaints (userid, name, email, id_passport, phone, address, city, issue, sub_issues, if_choice_is_other, issue_type, image_path, date_created)
                VALUES ('$userid', '$name', '$email', '$id_passport', '$phone', '$address', '$city', '$issue', '$sub_issues', '$otherIssueDiv', '$issue_type', '" . implode(",", $uploadedFiles) . "', '$currentDateTime')";

        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Form submitted successfully!"); window.location.href = "complaintForm.php";</script>';
            exit; 
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
$targetDir = "uploads/";
?>
