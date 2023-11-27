<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];
    $nationalid = $_POST["nationalid"];
    $entered_code = $_POST["verification_code"];

    // Check if the email and National ID exist in the database
    $query = "SELECT * FROM userlogincredentials WHERE email = '$email' AND national_id = '$nationalid'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 0) {
        // Email or National ID not found in the database, display an error message
        $_SESSION["error_message"] = "Email or National ID not found. Please check your email address and National ID.";
        header("Location: forgotpassword.php");
        exit();
    }

    // Retrieve the stored verification code and expiration time from the database
    $user_id_query = "SELECT userid FROM userlogincredentials WHERE email = '$email'";
    $user_id_result = mysqli_query($conn, $user_id_query);

    if ($user_id_result) {
        $user_id_row = mysqli_fetch_assoc($user_id_result);
        $user_id = $user_id_row["userid"];

        $verification_query = "SELECT code, expires_at FROM verification_codes WHERE user_id = '$user_id'";
        $verification_result = mysqli_query($conn, $verification_query);

        if ($verification_result) {
            $verification_row = mysqli_fetch_assoc($verification_result);
            $stored_code = $verification_row["code"];
            $expires_at = strtotime($verification_row["expires_at"]);
            $current_time = strtotime('now');

            if ($entered_code !== $stored_code || $current_time > $expires_at) {
                $_SESSION["error_message"] = "Invalid or expired verification code. Please request a new code.";
                header("Location: forgotpassword.php");
                exit();
            }

            // Validate the new password and confirmation
            if ($new_password !== $confirm_password) {
                $_SESSION["error_message"] = "Passwords do not match.";
                header("Location: forgotpassword.php");
                exit();
            }

            // Update the user's password in the database
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_query = "UPDATE userlogincredentials SET password = '$hashed_password' WHERE email = '$email'";
            $update_result = mysqli_query($conn, $update_query);

            if (!$update_result) {
                $_SESSION["error_message"] = "Password update failed. Please try again later.";
                header("Location: forgotpassword.php");
                exit();
            }

            // Password updated successfully, display a success message
            $_SESSION["success_message"] = "Password reset successful.";
            header("Location: forgotpassword.php");
            exit();
        } else {
            $_SESSION["error_message"] = "Failed to retrieve verification code. Please try again later.";
            header("Location: forgotpassword.php");
            exit();
        }
    } else {
        $_SESSION["error_message"] = "Failed to retrieve user information. Please try again later.";
        header("Location: forgotpassword.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <!-- Add your favicon link here -->
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">

    <!-- Add Bootstrap CSS link here -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        .container {
            margin-top: 20px;
            max-width: 500px;
        }
        .btn{
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-light bg-light justify-content-between">
    <a class="navbar-brand" href="#" style="margin-left: 10px;">
        <img src="Coat_of_Arms_of_Nairobi.svg.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Nairobi Reporting Portal
    </a>
    <a href="homepageviewnologin.php" style="color: #000; margin-right: 10px; text-decoration: none;">View Homepage Without Login</a>
</nav>
<div class="container">
    <h2 class="mt-4">Forgot Password</h2>

    <?php if (isset($_SESSION["error_message"])) : ?>
        <div class="alert alert-danger mt-3"><?php echo $_SESSION["error_message"]; ?></div>
        <?php unset($_SESSION["error_message"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["success_message"])) : ?>
        <div class="alert alert-success mt-3"><?php echo $_SESSION["success_message"]; ?></div>
        <?php unset($_SESSION["success_message"]); ?>
    <?php endif; ?>

    <form method="POST" action="forgotpassword.php" class="mt-3">
        <div class="mb-3">
            <label for="email" class="form-label">Email Address:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="button" class="btn btn-dark" onclick="sendVerificationCode()">Send Code</button>
        <!--field for checking national id-->
        <div class="mb-3">
            <label for="nationalid" class="form-label">National ID:</label>
            <input type="text" class="form-control" id="nationalid" name="nationalid" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password:</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm New Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <!-- Verification code input field -->
        <div class="mb-3">
            <label for="verification_code" class="form-label">Verification Code:</label>
            <input type="text" class="form-control" id="verification_code" name="verification_code" required>
        </div>
        <!-- Button to send verification code -->
        

        <button type="submit" class="btn btn-primary" name="reset_password">Reset Password</button>
        <!--button to redirect to residentlogin.html-->
        <div>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='residentlogin.html'">Back to Login</button>
        </div>

    </form>
</div>

<!-- Include Bootstrap 5.3.1 JavaScript if needed -->
<script>
    function sendVerificationCode() {
        var email = document.getElementById("email").value;

        // Check if the email is provided
        if (email.trim() === "") {
            alert("Please enter your email address first.");
            return;
        }

        // Use AJAX to send the email to your PHP script
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "send_verification_code.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Handle the response if needed
                alert(xhr.responseText);
            }
        };
        xhr.send("email=" + email);
    }
</script>
</body>
</html>
