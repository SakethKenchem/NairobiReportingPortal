<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">    
<title>Create a Post</title>
<link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">

<style>
        body {
            margin: 20px;
        }

        h1, h2, h3 {
            text-align: center;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="file"],
        textarea {
            margin-bottom: 10px;
        }

        .button {
            background-color: black;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            float: right;
        }

        .h3 {
            color: red;
        }

        .navbar-container {
            margin-top: 20px;
            background-color: green;
            border-radius: 5px;
            width: 1340px;
            height: 60px;
            margin-left: -15px;
        }

        .navbar-links {
            display: flex;
            flex-direction: row;
            font-size: large;
            color: white;
            margin-left: 20px;
            margin-top: 7px;
        }

        .navbar-links a {
            color: white;
            text-decoration: none;
        }

        .navbar-links a:hover {
            color: #f8f9fa;
        }

        .container {
            margin: 20px auto;
            max-width: 800px;
        }

        .btn {
            background-color: green;
            color: white;
        }
        .btn:hover{
            background-color: green;
            color: white;
        }
        .dropdown-toggle {
            background-color: #ffc107; 
            color: black; 
        }

        .dropdown-toggle:hover {
            background-color: #ffc107; 
            color: black; 
        }
    </style>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    echo "<script>alert('Please login to view this page'); window.location.href='residentlogin.html';</script>";
    exit;
}
?>
    <div class="navbar-container">
        <nav class="navbar navbar-dark container-fluid justify-content-between">
            <a class="navbar-brand" href="homepage.php" style="margin-left: 10px;">
                <img src="Coat_of_Arms_of_Nairobi.svg.png" width="30" height="30" class="d-inline-block align-top1" alt="">
                Nairobi Reporting Portal
            </a>
            <div class="navbar-links">
                <a class="nav-link" href="complaintForm.php" style="margin-right: 15px;">Complaint Form</a>
                <a class="nav-link" style="text-decoration: underline;" href="postCreate.php">Create Post</a>
                <div class="dropdown" style="margin-top: -3px;">
                    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-left: 15px; margin-right: 10px;">User Profile</button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="userprofilePage.php" style="color: black;">Update User Details</a>
                        <a class="dropdown-item" href="Userchangepassword.php" style="color: black;">Change Password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="mycomplaints.php" style="color: black;">My Complaints</a>
                        <a class="dropdown-item" href="myposts.php" style="color: black;">My Posts</a>
                        <a class="dropdown-item" href="mycomments.php" style="color: black;">My Comments</a>
                    </div>
                </div>
                <a class="nav-link" href="about_us.html" style="color: white; margin-right: 13px;">About Us</a> <!-- Add this line -->
                <a class="nav-link" href="logout.php" style="color: white; margin-right: 15px;">Logout</a>
            </div>
        </nav>
    </div>


<div class="container">
    <h1>Create a Post</h1>
    <p style="text-align: center; color: red; font-size:larger; margin-bottom: 10px;" class="h6">Do not upload inappropriate images and descriptions</p>
    <form method="POST" action="uploadPost.php" enctype="multipart/form-data" class="border p-4 rounded bg-light">
        <div class="form-group">
            <label for="image">Choose an image:</label>
            <input type="file" id="image" name="image" required class="form-control-file">
        </div>
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" class="form-control" value="<?php echo $_SESSION['username']; ?>">
        </div>

        <div>
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" required class="form-control" placeholder="Please input your approximate location">
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" required class="form-control" placeholder="Please give a description"></textarea>
        </div>

        <div>
            <button type="submit" class="btn">Create Post</button>
        </div>
    </form>
</div>

<script>
    const foulWords = ["fuck", "fucker", "asshole"]; // Add more foul words as needed

    function hasFoulLanguage(text) {
        for (let word of foulWords) {
            const regex = new RegExp('\\b' + word + '\\b', 'i');
            if (regex.test(text)) {
                return true;
            }
        }
        return false;
    }

    function validateForm(e) {
        e.preventDefault(); // Prevent the form from submitting

        const locationInput = document.getElementById("location");
        const descriptionInput = document.getElementById("description");

        if (hasFoulLanguage(locationInput.value) || hasFoulLanguage(descriptionInput.value)) {
            alert("Please do not use foul language in the location or description.");
        } else {
            e.target.submit(); // Submit the form if there's no foul language
        }
    }

    const form = document.querySelector("form");
    form.addEventListener("submit", validateForm);
</script>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
