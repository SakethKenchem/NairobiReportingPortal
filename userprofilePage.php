<!DOCTYPE html>
<html>
<head>
    <title>Update User Details</title>
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">    
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
        }

        .post-container {
            max-width: 365px;
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .post-container img {
            max-width: 100%;
        }
        .form-group{
            margin: 10px;
            width: 350px;
        }

        .post-container p {
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
        .navbar{
            max-width: 99%;
            border-radius: 5px;
            background-color: green;
        }
        .p{
            font-size: 15px;
            margin: auto;
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
                <a class ="dropdown-item" href="Userchangepassword.php">Change Password</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="mycomplaints.php">My Complaints</a>
                <a class="dropdown-item" href="myposts.php">My Posts</a>
                <a class ="dropdown-item" href="mycomments.php">My Comments</a>
            </div>
        </div>
        <a class="nav-link" href="about_us.html" style="color: white;">About Us</a>
        <a class="nav-link" href="logout.php" style="color: white;">Logout</a>
    </div>
</nav>
<div>
    <p class="p"><b style="color: red;">Note:</b> You are not allowed to change your Username and National ID. If you wish to change it then you must contact the admin <a href="Error_or_Suggestion_Portal.php">here</a></p>
</div>
<div class="container">
    <h2 class="my-4">Current User Details</h2>
    <?php
    session_start();
    if (!isset($_SESSION['loggedin']) || empty($_SESSION['loggedin'])) {
        echo '<div class="alert alert-danger mt-4">Please log in to view your user profile.</div>';
        exit();
    }

    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "isp";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to get user details from the database
    function getUserDetails($conn, $userId) {
        $sql = "SELECT *, phoneNumber FROM userlogincredentials WHERE userid = $userId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // Function to update user details in the database
// Function to update user details in the database
function updateUserDetails($conn, $username, $email, $nationalId, $phoneNumber, $security_phrase, $userId) {
    $sql = "UPDATE userlogincredentials SET username='$username', email='$email', national_id='$nationalId', phoneNumber='$phoneNumber', security_phrase_or_digit='$security_phrase' WHERE userid=$userId";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        $newUsername = $_POST["username"];
        $newEmail = $_POST["email"];
        $newNationalId = $_POST["national_id"];
        $phoneNumber = $_POST["phoneNumber"];
        //security phrase
        $security_phrase = $_POST['security_phrase']; // Corrected the variable name

        $loggedInUserId = $_SESSION["userid"];

        if (updateUserDetails($conn, $newUsername, $newEmail, $newNationalId, $phoneNumber, $security_phrase, $loggedInUserId)) {
            echo '<div class="alert alert-success mt-4">Your details were updated successfully!</div>';
        } else {
            echo '<div class="alert alert-danger mt-4">Error updating your details: ' . $conn->error . '</div>';
        }
    }

    $loggedInUserId = $_SESSION["userid"];

    $userDetails = getUserDetails($conn, $loggedInUserId);
    ?>

    <?php
    if ($userDetails) {
        echo '<div class="my-4">';
        echo '<p><strong>Current Username:</strong> ' . $userDetails["username"] . '</p>';
        echo '<p><strong>Current Email:</strong> ' . $userDetails["email"] . '</p>';
        echo '<p><strong>Current National ID/Passport:</strong> ' . $userDetails["national_id"] . '</p>';
        echo '<p><strong>Current Phone Number:</strong> ' . $userDetails["phoneNumber"] . '</p>';
        echo '<p><strong>Registered on:</strong> ' . $userDetails["account_created_at"] . '</p>';
        echo '</div>';
    } else {
        echo '<p>User details not found.</p>';
    }
    ?>

    <h2 class="my-4">Update User Details</h2>
    <form method="post" class="my-4">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username" id="username" value="<?php echo $username = $_SESSION['username']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" id="email" value="<?php echo isset($userDetails["email"]) ? $userDetails["email"] : ""; ?>">
        </div>
        <div class="form-group">
            <label for="national_id">National ID/Passport:</label>
            <input type="text" class="form-control" name="national_id" id="national_id" value="<?php echo $nationalId = $_SESSION['national_id']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="phoneNumber">Phone Number:</label>
            <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" value="<?php echo isset($userDetails["phoneNumber"]) ? $userDetails["phoneNumber"] : ""; ?>">
        </div>
        <!--security phrase-->
        <div id="securityPhraseContainer" style="display: none;">
    <p><strong>Security Phrase:</strong> <?php echo isset($userDetails["security_phrase_or_digit"]) ? $userDetails["security_phrase_or_digit"] : ""; ?></p>
</div>

<!-- Update the security phrase input to be password type -->
<div class="form-group">
    <label for="security_phrase">Security Phrase:</label>
    <input type="password" class="form-control" name="security_phrase" id="security_phrase" value="<?php echo isset($userDetails["security_phrase_or_digit"]) ? $userDetails["security_phrase_or_digit"] : ""; ?>">
</div>
        <div class="form-check">
    <input class="form-check-input" type="checkbox" id="showSecurityPhrase" onclick="toggleSecurityPhrase()">
    <label class="form-check-label" for="showSecurityPhrase">
        Show Security Phrase
    </label>
</div>
        <div>
            <button type="submit" class="btn btn-primary" name="submit">Update Details</button>
        </div>
        <div>
            <!--delete account button-->
            <a href="deleteaccount.php" class="btn btn-danger" style="margin-top: 5px;">Delete Account</a>
        </div>
    </form>
</div>
</body>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function toggleSecurityPhrase() {
        var checkbox = document.getElementById("showSecurityPhrase");
        var securityPhraseContainer = document.getElementById("securityPhraseContainer");
        var securityPhraseInput = document.getElementById("security_phrase");

        if (checkbox.checked) {
            securityPhraseContainer.style.display = "block";
            securityPhraseInput.type = "text";
        } else {
            securityPhraseContainer.style.display = "none";
            securityPhraseInput.type = "password";
        }
    }
</script>
</html>

<?php
$conn->close();
?>
