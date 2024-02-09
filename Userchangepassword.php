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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset_password"])) {
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
        header("Location: Userchangepassword.php");
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
                header("Location: Userchangepassword.php");
                exit();
            }

            // Validate the new password and confirmation
            if ($new_password !== $confirm_password) {
                $_SESSION["error_message"] = "Passwords do not match.";
                header("Location: Userchangepassword.php");
                exit();
            }

            // Update the user's password in the database
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $update_query = "UPDATE userlogincredentials SET password = '$hashed_password' WHERE email = '$email'";
            $update_result = mysqli_query($conn, $update_query);

            if (!$update_result) {
                $_SESSION["error_message"] = "Password update failed. Please try again later.";
                header("Location: Userchangepassword.php");
                exit();
            }

            // Remove the used verification code from the database
            $delete_verification_query = "DELETE FROM verification_codes WHERE user_id = '$user_id'";
            mysqli_query($conn, $delete_verification_query);

            // Password updated successfully, display a success message
            $_SESSION["success_message"] = "Password reset successful.";
            header("Location: Userchangepassword.php");
            exit();
        } else {
            $_SESSION["error_message"] = "Failed to retrieve verification code. Please try again later.";
            header("Location: Userchangepassword.php");
            exit();
        }
    } else {
        $_SESSION["error_message"] = "Failed to retrieve user information. Please try again later.";
        header("Location: Userchangepassword.php");
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
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            margin-bottom: 15px;
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

        .form-control {
            width: 350px;
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
        <h2 class="mt-4">Forgot Password</h2>

        <?php if (isset($_SESSION["error_message"])) : ?>
            <div class="alert alert-danger mt-3"><?php echo $_SESSION["error_message"]; ?></div>
            <?php unset($_SESSION["error_message"]); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION["success_message"])) : ?>
            <div class="alert alert-success mt-3"><?php echo $_SESSION["success_message"]; ?></div>
            <?php unset($_SESSION["success_message"]); ?>
        <?php endif; ?>

        <form method="POST" action="Userchangepassword.php" class="mt-3">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="button" class="btn btn-dark" onclick="sendVerificationCode()">Send Code</button>

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

            <div class="mb-3">
                <label for="verification_code" class="form-label">Verification Code:</label>
                <input type="text" class="form-control" id="verification_code" name="verification_code" required>
            </div>

            <button type="submit" class="btn btn-primary" name="reset_password">Reset Password</button>
        </form>
    </div>

    <script>
        function sendVerificationCode() {
            var email = document.getElementById("email").value;

            if (email.trim() === "") {
                alert("Please enter your email address first.");
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "send_verification_code.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                }
            };
            xhr.send("email=" + email);
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>