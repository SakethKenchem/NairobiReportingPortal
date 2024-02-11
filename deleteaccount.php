<?php

session_start();


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: residentlogin.html");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";


$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_account"])) {
    $email = $_POST["email"];
    $nationalId = $_POST["national_id"];
    $password = $_POST["password"];


    $query = "SELECT * FROM userlogincredentials WHERE email = '$email' AND national_id = '$nationalId'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) === 0) {
        $_SESSION["error_message"] = "Email not found. Please check your email address.";
    } else {
        $user_data = mysqli_fetch_assoc($result);

        if (password_verify($password, $user_data["password"])) {
            $user_id = $user_data["userid"];
            $delete_comments_query = "DELETE FROM comments WHERE userid = '$user_id'";
            $delete_comments_result = mysqli_query($conn, $delete_comments_query);

            if (!$delete_comments_result) {
                $_SESSION["error_message"] = "Error deleting comments.";
            } else {
                $delete_query = "DELETE FROM userlogincredentials WHERE email = '$email'";
                $delete_result = mysqli_query($conn, $delete_query);

                if (!$delete_result) {
                    $_SESSION["error_message"] = "Account deletion failed. Please try again later.";
                } else {
                    $_SESSION["success_message"] = "Account deleted successfully.";
                    header("Location: logout.php");
                    exit();
                }
            }
        } else {
            $_SESSION["error_message"] = "Incorrect password. Please try again.";
        }
    }

    header("Location: deleteaccount.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Account</title>
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    
    <style>
        .container {
            margin-top: 20px;
            max-width: 500px;
        }
        .btn {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-light bg-light justify-content-between">
    <a class="navbar-brand" href="homepage.php" style="margin-left: 10px;">
        <img src="Coat_of_Arms_of_Nairobi.svg.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Nairobi Reporting Portal
    </a>
    <a href="Error_or_Suggestion_Portal.php" class="alert alert-dark" style="margin-right: 15px; text-decoration: none;">Report any issues with the system </a>
</nav>
<div class="container">
    <h2 class="mt-4">Delete Account</h2>

    <?php if (isset($_SESSION["error_message"])) : ?>
        <div class="alert alert-danger mt-3"><?php echo $_SESSION["error_message"]; ?></div>
        <?php unset($_SESSION["error_message"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["success_message"])) : ?>
        <div class="alert alert-success mt-3"><?php echo $_SESSION["success_message"]; ?></div>
        <?php unset($_SESSION["success_message"]); ?>
    <?php endif; ?>

    <form method="POST" action="deleteaccount.php" class="mt-3">
        <div class="mb-3">
            <label for="email" class="form-label">Email Address:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div>
            <label for="national_id" class="form-label">National ID:</label>
            <input type="text" class="form-control" id="national_id" name="national_id" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div style="text-align: justify;">
            <p><b style="color: red;">Note:</b> Deleting an account is an irreversible action.<br>
            <div>
                All of your <b>complaints</b>, <b>posts</b>, votes, comments, and reports to the admin shall be <b style="color: red;">deleted</b> as well.<br>
            </div>
            </p>
        </div>
        <div>
            <button type="submit" class="btn btn-danger" name="delete_account">Delete Account</button>
        </div>
        <div>
            <button type="button" class="btn btn-secondary" onclick="window.location.href='userprofilePage.php'">Back to Profile</button>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
