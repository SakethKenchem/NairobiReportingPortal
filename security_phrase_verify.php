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

    // Check if user is not logged in
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
        .alert{
            width: 50%;
            margin-top: 5%;
            margin-left: 350px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Verify Security Phrase</h1>
        <form action="security_phrase_verify.php" method="POST">
            <div class="mb-3">
                <label for="security_phrase" class="form-label">Security Phrase</label>
                <input type="password" class="form-control" id="security_phrase" name="security_phrase">
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $security_phrase = $_POST['security_phrase']; // Corrected the variable name

        $sql = "SELECT * FROM userlogincredentials WHERE security_phrase_or_digit = '$security_phrase'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $stored_security_phrase = $row['security_phrase_or_digit']; // Corrected the column name

            if ($security_phrase == $stored_security_phrase) {
                $_SESSION['security_phrase'] = $security_phrase;
                header("Location: homepage.php");
                exit();
            } else {
                //bootstrap alert for incorrect security phrase
                echo "<div class='alert alert-danger' role='alert'>Incorrect security phrase!</div>";
            }
        } else {
                //bootstrap alert for incorrect security phrase
                echo "<div class='alert alert-danger' role='alert'>
                Incorrect security phrase!
                </div>";
        }
    }
    ?>
</body>
</html>
