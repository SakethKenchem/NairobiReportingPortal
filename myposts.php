<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Posts</title>
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
            max-width: 400px;
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .post-container img {
            max-width: 100%;
            height: 100px;
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

        .carousel-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .carousel-inner{
            max-width: 400px;
            max-height: 200px;
            margin: 0 auto;
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
                <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    User Profile
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="userprofilePage.php" >Update User Details</a>
                    <a class="dropdown-item" href="Userchangepassword.php">Change Password</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="mycomplaints.php">My Complaints</a>
                    <a class="dropdown-item" href="myposts.php">My Posts</a>
                    <a class="dropdown-item" href="mycomments.php">My Comments</a>
                </div>
            </div>
            <a class="nav-link" href="about_us.html" style="color: white;">About Us</a>
            <a class="nav-link" href="logout.php" style="color: white;">Logout</a>
        </div>
    </nav>
    <h2 style="margin-top: 5px;">Your Posts</h2>

    <div class="container mt-4">
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

        $sql = "SELECT posts.postid, posts.description, posts.location, posts.datecreated, GROUP_CONCAT(post_images.file_path) AS image_paths
                FROM posts
                INNER JOIN post_images ON posts.postid = post_images.post_id
                WHERE posts.userid = '$userid'
                GROUP BY posts.postid";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Post ID</th>
                            <th>Images</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . $row['postid'] . '</td>
                        <td>
                            <div id="carouselExampleIndicators' . $row['postid'] . '" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">';

                // Split image paths by comma and create carousel items
                $imagePaths = explode(',', $row['image_paths']);
                $firstImage = true;

                foreach ($imagePaths as $imagePath) {
                    echo '<div class="carousel-item' . ($firstImage ? ' active' : '') . '">
                            <img src="' . $imagePath . '" class="d-block" style="width: 400px; height: 200px;" alt="Post Image">
                          </div>';
                    $firstImage = false;
                }

                echo '</div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators' . $row['postid'] . '" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators' . $row['postid'] . '" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </td>
                        <td>' . $row['description'] . '</td>
                        <td>' . $row['location'] . '</td>
                        <td>' . $row['datecreated'] . '</td>
                    </tr>';
            }

            echo '</tbody>
                    </table>';
        } else {
            echo '<div class="alert alert-info mt-4">No posts found.</div>';
        }

        $conn->close();
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>

</html>
