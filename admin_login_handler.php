<?php
session_name("admin_session");
session_start();

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'isp';

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if (isset($_POST["signup"])) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO admin_credentials (username, password) VALUES ('$username', '$hashedPassword')";
        $conn->query($query);

        header("Location: admin_login_signup.php");
        exit();
    } else if (isset($_POST["login"])) {
        $query = "SELECT * FROM admin_credentials WHERE username = '$username'";
        $result = $conn->query($query);

        if ($result->num_rows === 1) {
            $admin_data = $result->fetch_assoc();
            if (password_verify($password, $admin_data['password'])) {
                session_start();
                //place adminid in session
                $_SESSION["adminid"] = $admin_data['adminid'];
                $_SESSION["username"] = $username;
                $_SESSION["admin_authenticated"] = true;

                header("Location: adminuserView.php");
                exit();
            }
        } else {
            echo '<script>alert("Incorrect password. Please try again."); window.location.href = "admin_login_signup.php?error=1";</script>';
            exit();
        }

        echo '<script>alert("Authentication failed. Please check your credentials."); window.location.href = "admin_login_signup.php?error=1";</script>';
        exit();
    }
}

$conn->close();
?>
