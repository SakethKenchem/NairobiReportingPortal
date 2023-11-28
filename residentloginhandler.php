<?php
$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'isp';

$conn = new mysqli($server, $username, $password, $dbname);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user is blocked
    $check_blocked_sql = "SELECT is_blocked FROM userlogincredentials WHERE username = '$username'";
    $result = mysqli_query($conn, $check_blocked_sql);
    $row = mysqli_fetch_assoc($result);

    if ($row['is_blocked'] == 1) {
        echo "<script>alert('User is blocked due to policy violations.');
        window.location.href='residentlogin.html';
        </script>";
        exit();
    }

    // Retrieve user details
    $get_user_sql = "SELECT userid, password, national_id FROM userlogincredentials WHERE username = '$username'";
    $result = mysqli_query($conn, $get_user_sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];
        $stored_national_id = $row['national_id'];

        if (password_verify($password, $hashed_password)) {
            $entered_national_id = $_POST['national_id']; 
            if ($entered_national_id === $stored_national_id) {
                session_start();
                $_SESSION['userid'] = $row['userid'];
                $_SESSION['username'] = $username;
                $_SESSION['national_id'] = $stored_national_id;
                $_SESSION['loggedin'] = true;
                

                $userid = $row['userid'];
                $login_time = date("Y-m-d H:i:s"); 
                $insert_login_time_sql = "INSERT INTO userloginhistory (userid, login_time) VALUES ($userid, '$login_time')";
                mysqli_query($conn, $insert_login_time_sql);
                
                header("Location: security_phrase_verify.php");
                exit();
            } else {
                echo "<script>alert('You have entered an incorrect username, National ID, or password. Please try again.');
                window.location.href='residentlogin.html';
                </script>";
                exit();
            }
        } else {
            echo "<script>alert('You have entered an incorrect username, National ID, or password. Please try again.');
            window.location.href='residentlogin.html';
            </script>";
            exit();
        }
    } else {
        echo "<script>alert('You have entered an incorrect username, National ID, or password. Please try again.');
        window.location.href='residentlogin.html';
        </script>";
        exit();
    }
}
?>
