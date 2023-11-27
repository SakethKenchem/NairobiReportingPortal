<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Error or Suggestion</title>
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-light bg-light justify-content-between">
    <a class="navbar-brand" href="homepage.php" style="margin-left: 10px;">
        <img src="Coat_of_Arms_of_Nairobi.svg.png" width="30" height="30" class="d-inline-block align-top" alt="">
        Nairobi Reporting Portal
    </a>
</nav>
    <div class="container mt-4">
        <h2>Report Error or Suggestion</h2>
        <?php
            session_start(); // Start the session

            // Check if the user is logged in and retrieve their userid
            if(isset($_SESSION['userid'])){
                $userid = $_SESSION['userid'];
            } else {
                // If the user is not logged in, you can handle it accordingly
                // For example, redirect them to a login page
                header("Location: residentlogin.html"); // Replace "login.php" with your actual login page
                exit();
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Database connection settings
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "isp";

                // Create a database connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Get data from the form
                $name = $_POST["name"];
                $email = $_POST["email"];
                $subject = $_POST["subject"];
                $message = $_POST["message"];

                // Escape and sanitize input (not a replacement for prepared statements)
                $name = $conn->real_escape_string($name);
                $email = $conn->real_escape_string($email);
                $subject = $conn->real_escape_string($subject);
                $message = $conn->real_escape_string($message);

                // SQL query to insert the report data into the database, including userid
                $sql = "INSERT INTO reports_to_admin (name, email, subject, message, userid) VALUES ('$name', '$email', '$subject', '$message', $userid)";

                // Debugging: Output the SQL query before execution
                //echo "SQL Query: " . $sql;

                // Execute the query
                if ($conn->query($sql) === TRUE) {
                    echo '<div class="alert alert-success">Report submitted successfully.</div>';
                } else {
                    echo '<div class="alert alert-danger">An error occurred while submitting the report: ' . $conn->error . '</div>';
                }

                // Close the database connection
                $conn->close();
            }
        ?>
        <form method="POST">
        <input type="hidden" name="userid" value="<?php echo $_SESSION['userid']; ?>"> 
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email (optional):</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Subject:</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message:</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <div style="margin-top: 5px;">
                <a href="userprofilePage.php" class="btn btn-secondary">Back to profile</a>
            </div>
        </form>
    </div>
</body>
</html>
