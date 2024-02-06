<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Complaints</title>
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">

    <!-- Include Bootstrap 5 CSS -->
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
            border: 1px solid black;
            border-radius: 3px;
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

        .navbar {
            max-width: 99%;
            border-radius: 5px;
            background-color: green;
        }
        .container{
            margin-bottom: 20px;
            margin-left: 15px;
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
<a class="nav-link" href="about_us.html" style="color: white;">About Us</a> <!-- Add this line -->

    <a class="nav-link" href="logout.php" style="color: white;">Logout</a>
    </div>
</nav>
<?php
session_start();
    
if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
    echo '<div class="alert alert-danger mt-4">Please log in to view your complaints. <a href="residentlogin.html">Here</a></div>';
    exit(); 
}

// this code fetches complaints for the current logged in user from the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userid = $_SESSION['userid'];

$sql = "SELECT * FROM complaints WHERE userid = '$userid'";
$result = $conn->query($sql);

$complaints = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
}

$sql = "SELECT complaints.*, GROUP_CONCAT(complaint_images.file_path) AS image_paths
        FROM complaints
        LEFT JOIN complaint_images ON complaints.complaint_id = complaint_images.complaint_id
        WHERE complaints.userid = '$userid'
        GROUP BY complaints.complaint_id";

$result = $conn->query($sql);

$complaints = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
}

//function to delete complaint
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM complaints WHERE complaint_id = '$id'";
    $result = $conn->query($sql);
    if ($result) {
        echo '<div class="alert alert-success mt-4">Complaint deleted successfully.</div>';
        // Redirect back to the same page to avoid re-deletion on page refresh
        header("Location: mycomplaints.php");
        exit();
    } else {
        echo '<div class="alert alert-danger mt-4">An error occurred while deleting complaint.</div>';
    }
}
?>
<h2 class="text-center mb-4" style="margin-top: 5px;">My Complaints</h2>
<div class="container mt-4" style="font-size:small;">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>National ID or Passport Number</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Locality</th>
                <th>Issue Type</th>
                <th>Issue</th>
                <th>Image</th>
                <th>Date Created</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
$count = 1;
foreach ($complaints as $complaint) {
    echo '<tr>';
    echo '<td>' . $count++ . '</td>';
    echo '<td>' . $complaint["name"] . '</td>';
    echo '<td>' . $complaint["email"] . '</td>';
    echo '<td>' . $complaint["id_passport"] . '</td>';
    echo '<td>' . $complaint["phone"] . '</td>';
    echo '<td>' . $complaint["address"] . '</td>';
    echo '<td>' . $complaint["city"] . '</td>';
    echo '<td>' . $complaint["issue_type"] . '</td>';
    echo '<td>' . $complaint["issue"] . '</td>';
    echo '<td>';

    // Check if the complaint has multiple images
    if (!empty($complaint["image_paths"])) {
        $imagesArray = explode(',', $complaint["image_paths"]);
        if (count($imagesArray) > 1) {
            echo '<div id="carouselExampleControls_' . $complaint["complaint_id"] . '" class="carousel slide" data-bs-ride="carousel">';
            echo '<div class="carousel-inner">';
            foreach ($imagesArray as $index => $imagePath) {
                $activeClass = ($index === 0) ? 'active' : '';
                echo '<div class="carousel-item ' . $activeClass . '">';
                echo '<img src="' . $imagePath . '" class="d-block" style="width="400px; height="100px;" " " alt="Complaint Image">';
                echo '</div>';
            }
            echo '</div>';
            echo '<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls_' . $complaint["complaint_id"] . '" data-bs-slide="prev">';
            echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
            echo '<span class="visually-hidden">Previous</span>';
            echo '</button>';
            echo '<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls_' . $complaint["complaint_id"] . '" data-bs-slide="next">';
            echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
            echo '<span class="visually-hidden">Next</span>';
            echo '</button>';
            echo '</div>';
        } else {
            // Display single image without carousel
            echo '<img src="' . $complaint["image_paths"] . '" alt="Complaint Image" width="200px" height="100px">';
        }
    } else {
        // Handle the case when "image_paths" is empty or not present
        echo 'No images available';
    }

    echo '</td>';
    echo '<td>' . $complaint["date_created"] . '</td>';
    echo '<td>' . $complaint["status"] . '</td>';
    // Buttons inside the Actions column
    echo '<td>';
    echo '<a href="editcomplaint.php?id=' . $complaint["complaint_id"] . '" class="btn btn-primary">Edit</a>';
    echo '<a href="javascript:void(0);" onclick="confirmDelete(' . $complaint["complaint_id"] . ');" class="btn btn-danger" style="margin-top: 5px;">Delete</a>';
    echo '</td>';
    echo '</tr>';
}
?>
        </tbody>
    </table>
</div>
</body>

<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this complaint?")) {
            window.location.href = "mycomplaints.php?id=" + id;
        }
    }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>

</html>
