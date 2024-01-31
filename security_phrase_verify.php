<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Error: " . mysqli_connect_error());
}


if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirect to the login page if not logged in
    header("Location: residentlogin.html");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <title>Verify Security Phrase</title>

    <style>
        .container {
            width: 50%;
            margin-top: 5%;
        }
        .alert {
            width: 50%;
            margin-top: 5%;
            margin-left: 350px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Verify Security Phrase</h1>
        <form action="security_phrase_verify.php" method="POST" id="verificationForm">
            <div class="mb-3">
                <label for="security_phrase" class="form-label">Security Phrase</label>
                <input type="password" class="form-control" id="security_phrase" name="security_phrase" placeholder="Security Phrase" required>
            </div>
            <button type="submit" class="btn btn-primary" id="verifyBtn">Verify</button>
            
            <div class="d-flex justify-content-center">
            <div class="spinner-border text-primary" role="status" style="display: none;" id="spinner"></div>
            </div>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $security_phrase = $_POST['security_phrase'];

        $sql = "SELECT * FROM userlogincredentials WHERE security_phrase_or_digit = '$security_phrase'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $stored_security_phrase = $row['security_phrase_or_digit'];

            if ($security_phrase == $stored_security_phrase) {
                $_SESSION['security_phrase'] = $security_phrase;
                // Show spinner for 2 seconds
                echo "<script>
                        document.getElementById('verifyBtn').style.display = 'none';
                        document.getElementById('spinner').style.display = 'block';
                        setTimeout(function(){
                            window.location.href = 'homepage.php';
                        }, 2000);
                      </script>";
            } else {
                
                echo "<div class='alert alert-danger' role='alert'>Incorrect security phrase!</div>";
            }
        } else {
            
            echo "<div class='alert alert-danger' role='alert'>
                Incorrect security phrase!
                </div>";
        }
    }
    ?>

    <!-- Add Bootstrap JS and Popper.js scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
