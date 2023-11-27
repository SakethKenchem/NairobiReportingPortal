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

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: residentlogin.html");
    exit;
}

if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    echo '<div class="alert alert-danger mt-4">Please log in to change your password.</div>';
    exit();
}

$errorMessage = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_password"])) {
    $emailAddress = $_POST["email"];
    $nationalID = $_POST["national_id"];
    $currentPassword = $_POST["current_password"];
    $newPassword = $_POST["new_password"];

    $loggedInUserId = $_SESSION["userid"];

    // Check if the provided email and national ID match the records in the database
    $sql = "SELECT email, national_id FROM userlogincredentials WHERE userid = $loggedInUserId";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($emailAddress === $row["email"] && $nationalID === $row["national_id"]) {
        // Proceed with password update logic
        $sql = "SELECT password FROM userlogincredentials WHERE userid = $loggedInUserId";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $currentHashedPassword = $row["password"];

        // Verify the current password with the hashed password
        if (password_verify($currentPassword, $currentHashedPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $sql = "UPDATE userlogincredentials SET password = '$hashedPassword' WHERE userid = $loggedInUserId";

            if ($conn->query($sql) === TRUE) {
                echo '<div class="alert alert-success mt-4">Password updated successfully!</div>';
            } else {
                echo '<div class="alert alert-danger mt-4">Error updating password: ' . $conn->error . '</div>';
            }
        } else {
            $errorMessage = "Invalid current password.";
        }
    } else {
        $errorMessage = "Email and/or National ID do not match the records.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
        }

        .form-container {
            max-width: 365px;
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .form-container img {
            max-width: 100%;
        }

        .form-group {
            margin: 10px;
            width: 350px;
        }

        .form-container p {
            margin: 10px 0;
        }

        .navbar-links {
            display: flex;
            flex-direction: row;
            gap: 16px;
            font-size: large;
            color: white;
            margin: left;
            margin-right: 20px;
            margin-top: 7px;
        }

        .navbar {
            max-width: 99%;
            border-radius: 5px;
            background-color: green;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark container-fluid justify-content-between">
    <a class="navbar-brand" href="homepage.php" style="margin-left: 10px;">
        <img src="Coat_of_Arms_of_Nairobi.svg.png" width="30" height="30" class="d-inline-block align-top1" alt="">
        Nairobi Reporting Portal
    </a>
    <div class="navbar-links">
        <a class="nav-link" href="complaintForm.php" style="color: white;">Complaint Form</a>
        <a class="nav-link" href="postCreate.php" style="color: white;">Create Post</a>
        <div class="dropdown" style="margin-top: -4px;">
            <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                User Profile
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="userprofilePage.php">Update User Details</a>
                <a class="dropdown-item" href="Userchangepassword.php">Change Password</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="mycomplaints.php">My Complaints</a>
                <a class="dropdown-item" href="myposts.php">My Posts</a>
                <a class="dropdown-item" href="mycomments.php">My Comments</a>
            </div>
        </div>
        <a class="nav-link" href="about_us.html" style="color: white;">About Us</a>
        <a class="nav-link" href="logout.php" style="color: white;">Logout</a>
    </div>
</nav>
<div class="container">
    <h1 class="my-4">Change Password</h1>
    <?php
    if (!empty($errorMessage)) {
        echo '<div class="alert alert-danger mt-4">' . $errorMessage . '</div>';
    }
    ?>
    <form method="post" class="my-4">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="national_id">National ID:</label>
            <input type="text" class="form-control" name="national_id" id="national_id" required>
        </div>
        <div class="form-group">
            <label for="current_password">Current Password:</label>
            <input type="password" class="form-control" name="current_password" id="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" class="form-control" name="new_password" id="new_password" required>
        </div>
        <div>
            <button type="submit" class="btn btn-danger" name="update_password" style="margin-top: 10px;">Update Password</button>
        </div>
    </form>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>
