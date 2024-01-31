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

    // Upload images code block
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
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                <style>
                    #spinner {
                        display: block;
                    }
            
                    #successTick {
                        display: none;
                        font-size: 80px;
                        color: green; /* Set the color to green */
                    }
            
                    #successText {
                        display: none;
                    }
                </style>
                <title>Form Submission</title>
            </head>
            <body>
                <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
                    <div id="spinner" class="spinner-border text-primary" role="status">
                        <span class="sr-only">Submitting...</span>
                    </div>
                    <div id="successTick" class="text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                  </svg>
                    </div>
                    <div id="successText" class="ml-3">Form Submitted Successfully!</div>
                </div>
            
                <script>
                    setTimeout(function() {
                        document.getElementById("spinner").style.display = "none";
                        document.getElementById("successTick").style.display = "block";
                        document.getElementById("successText").style.display = "block";
                    }, 2000); // 2000 milliseconds (2 seconds) delay
            
                    setTimeout(function() {
                        window.location.href = "complaintForm.php";
                    }, 3200); // 2000 milliseconds (5 seconds) total delay
                </script>
            </body>
            </html>
                      
            ';
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
$targetDir = "uploads/";
?>
