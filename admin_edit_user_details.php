<?php
session_name("admin_session");
session_start();

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'isp';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["admin_authenticated"]) || $_SESSION["admin_authenticated"] !== true) {
    header("Location: admin_login_signup.php");
    exit();
}

$user_id = null; // Initialize the user_id variable

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["user_id"])) {
    $user_id = $_GET["user_id"];
    $sql = "SELECT * FROM userlogincredentials WHERE userid = " . $user_id;
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        // Handle the case where the user doesn't exist or there's an error
        header("Location: adminuserView.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"])) {
    // Handle the form submission to update user details
    $user_id = $_POST["user_id"];
    if (isset($_POST["username"]) && isset($_POST["email"])) {
        $newUsername = $_POST["username"];
        $newEmail = $_POST["email"];
        $newNationalID = $_POST["national_id"];
        

        $updateSql = "UPDATE userlogincredentials SET username = '$newUsername', email = '$newEmail', national_id = '$newNationalID' WHERE userid = $user_id";
        
        if ($conn->query($updateSql) === TRUE) {
            // Redirect back to adminuserView.php or display a success message
            header("Location: adminuserView.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <style>
        .container {
            max-width: 500px;
            padding: 2px;
            margin-left: 90px;
        }
    </style>
</head>
<body>
    
    <div class="container mt-5">
        <h2>Edit User Details</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>">
            </div>
            <div class="mb-3">
                <label for="national_id" class="form-label">National ID / Passport Number:</label>
                <input type="text" class="form-control" id="national_id" name="national_id" value="<?php echo $user['national_id']; ?>">
            </div>


            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <div style="margin-top: 3px;">
            <button type="button" class="btn btn-secondary" onclick="window.location.href = 'adminuserView.php';">Cancel</button>
            </div>
        </form>
    </div>

</body>
</html>

