<!DOCTYPE html>
<html>
<head>
    <title>My Comments</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            margin-bottom: 100px; /* Add margin at the bottom to prevent overlap with the posts */
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

    </style>
</head>
<body>
<nav class="navbar navbar-dark container-fluid justify-content-between">
        <a class="navbar-brand" href="homepage.php">
            <img src="Coat_of_Arms_of_Nairobi.svg.png" width="30" height="30" class="d-inline-block align-top1"
                alt="" style="margin-left: 10px;">
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
            
            <a class="nav-link" href="about_us.html" style="color: white;">About Us</a>
            <a class="nav-link" href="logout.php" style="color: white;">Logout</a>
        </div>
    </nav>
    <div class="container">
        <h1>My Comments</h1>

        <div class="comment-list">
            <?php
            // Start or resume a session
            session_start();

            if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
                // Give a Bootstrap alert if not logged in
                echo '<div class="alert alert-danger" role="alert">
                    You must be logged in to view this page. Please <a href="residentlogin.html">login</a>
                </div>';
                exit;
            }

            // Retrieve the user's ID from the session
            $user_id = $_SESSION["userid"];

            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "isp";

            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Handle comment deletion
            if (isset($_POST['delete_comment'])) {
                $comment_id = $_POST['comment_id'];
                $sql_delete_comment = "DELETE FROM comments WHERE comment_id = $comment_id AND userid = $user_id";
                $conn->query($sql_delete_comment);
            }

            // Fetch and display user's comments with associated post details
            $sql_comments = "SELECT comments.comment_id, comments.comment, posts.description, posts.location, posts.datecreated, posts.username as post_creator_username
                            FROM comments
                            INNER JOIN posts ON comments.postid = posts.postid
                            WHERE comments.userid = $user_id";

            $result_comments = $conn->query($sql_comments);

            if ($result_comments->num_rows > 0) {
                echo '<table class="table table-bordered">
                        <thead>
                            <tr>
                            <th>Post Creator</th>
                                <th>Post Description</th>
                                <th>Location</th>
                                <th>Your Comment</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';

                while ($row = $result_comments->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['post_creator_username'] . '</td>';
                    echo '<td>' . $row['description'] . '</td>';
                    echo '<td>' . $row['location'] . '</td>';
                    echo '<td>' . $row['comment'] . '</td>';
                    echo '<td>' . $row['datecreated'] . '</td>';

                    echo '<td>
                            <form method="post">
                                <input type="hidden" name="comment_id" value="' . $row['comment_id'] . '">
                                <button type="submit" name="delete_comment" class="btn btn-danger">Delete</button>
                            </form>
                          </td>';
                    echo '</tr>';
                }

                echo '</tbody></table>';
            } else {
                echo '<p>No comments found.</p>';
            }
            ?>
        </div>
    </div>
    
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include Bootstrap JavaScript (jQuery and Popper.js are required) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+mvFzp5pq0iP6wLolv2pxPzjsst5rBjMc+6lFk5ECn00t2P" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.3/umd/popper.min.js" integrity="sha384-Y8c24FU/6T5IuF6F1axhiaODfzumVqFEGQb5uz0nOoBH2PMSAMny7Jq5k5T5e7fVp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-k3Fjn/U4zSfF92xg6v5g5vB0sJzgb3+pwHl18YqqyDd6s93W6N5bIUbYXslENs6Jm" crossorigin="anonymous"></script>
</body>
</html>
