<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Posts</title>
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">    <style>
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

        .comments-section {
            margin-top: 20px;
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
<h2 style="margin-top: 5px;">Your Posts</h2>

    <div class="container mt-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Post ID</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "isp";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                session_start();

                if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
                    echo '<div class="alert alert-danger mt-4">Please log in to view your complaints.</div>';
                    exit(); 
                }

                $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';

                // code retrieves posts created by the logged-in user
                $sql = "SELECT * FROM posts WHERE userid = '$userid'";
                $result = $conn->query($sql);
                                //function to delete post
                if (isset($_GET['id'])) {
                    $id = $_GET['id'];
                    $sql = "DELETE FROM posts WHERE postid = '$id'";
                    $result = $conn->query($sql);
                        if ($result) {
                            echo '<div class="alert alert-success mt-4">Post deleted successfully.</div>';
                            // Redirect back to the same page to avoid re-deletion on page refresh
                            
                        exit();
                        } else {
                            echo '<div class="alert alert-danger mt-4">An error occurred while deleting post.</div>';
                        }
                }

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['postid'] . "</td>";
                        echo "<td><img src='" . $row['image_path'] . "' width='200px' height='115px'></td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>" . $row['datecreated'] . "</td>";
                        //place the buttons inside actions column
                        echo "<td>";
                        echo "<a href='editpost.php?id=" . $row['postid'] . "' class='btn btn-primary mr-1'>Edit</a>";
                        //delete button that will use the delete function
                        echo "<a href='myposts.php?id=" . $row['postid'] . "' class='btn btn-danger' style='margin-left:5px;'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No posts found.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>
