<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // give a Bootstrap alert if not logged in
    echo '<div class="alert alert-danger" role="alert">
        You must be logged in to view this page. Please <a href="residentlogin.html">login</a>
    </div>';
    exit;
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";
$conn = new mysqli($servername, $username, $password, $dbname);

$username = $_SESSION['username'];

$result = $conn->query("SELECT * FROM posts ORDER BY postid DESC");

function getSeverityLevel($votes)
{
    if ($votes >= 50 && $votes <= 74) {
        return '<b><p style="color: orange;">Moderate</p></b>';
    } elseif ($votes >= 75 && $votes <= 134) {
        return '<b><p style="color: #9c140c;">Serious!</p></b>';
    } elseif ($votes >= 135 && $votes <= 180) {
        return '<b><p style="color: red;">Severe, Urgent Action Required Immediately!</p></b>';
    } else {
        return "Not High Enough so not Urgent!";
    }

}

if (isset($_POST["search"])) {
    $search = $_POST["search"];
    $search = $conn->real_escape_string($search); 

    
    $result = $conn->query("SELECT * FROM posts 
                            WHERE username LIKE '%$search%' 
                            OR location LIKE '%$search%' 
                            OR votes >= 50 AND votes <= 74 AND description LIKE '%$search%'
                            OR votes >= 75 AND votes <= 134 AND description LIKE '%$search%'
                            OR votes >= 135 AND votes <= 150 AND description LIKE '%$search%'
                            ORDER BY postid DESC");
} else {
    
    $result = $conn->query("SELECT * FROM posts ORDER BY postid DESC");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Home</title>
    <!-- Favicon image -->
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="dark-mode.css" id="dark-mode-styles">

    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            margin-bottom: 100px; 
        }

        .post-container {
            max-width: 380px;
            padding: 10px;
            margin-top: 50px;
            border: 1px solid #ccc;
            margin-bottom: 20px; 
            z-index: 1; 
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

        .greeting-container {
            animation: slideLeftToRight 1.15s forwards; 
            margin-top: 5px;
            margin-right: 1000px;
            margin-left: 20px;
        }

        @keyframes slideLeftToRight {
            from {
                transform: translateX(-100%); 
                opacity: 0;
            }

            to {
                transform: translateX(0); 
                opacity: 1;
            }
        }

        .greeting-font {
            font-size: x-large;
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


    <!-- Search bar -->
    <form action="homepage.php" method="POST" style="margin-top: 10px; margin-left: 1050px; height: 1px">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search for Posts" name="search"
                aria-label="Recipient's username" aria-describedby="button-addon2">
            <div class="input-group-append">
                <button class="btn btn-dark" type="submit" id="button-addon2" style="margin-left: 3px;">
                    <i class="fa-solid fa-magnifying-glass fa-fade"></i>
                </button>
            </div>
        </div>
    </form>

    <!--  greeting  -->
    <div class="greeting-container">
        <?php
        date_default_timezone_set('Africa/Nairobi');
        $time = date("H");
        if ($time < "12") {
            echo "<h3 class='greeting-font'>Good Morning  " . $username . "👋" ."</h3>";
        } elseif ($time >= "12" && $time < "17") {
            echo "<h3 class='greeting-font'>Good Afternoon  " . $username . "👋" . "</h3>";
        } elseif ($time >= "17") {
            echo "<h3 class='greeting-font'>Good Evening  " . $username . "👋" . "</h3>";
        } else {
            echo "Error displaying time";
        }
        ?>
    </div>

    <h4 style="margin-top: 3px; margin-right: 1058px; margin-left: 19.7px;">Here are the latest posts:</h4>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <?php
        $postId = $row["postid"];
        $hasVoted = isset($_SESSION["voted_posts"][$postId]);
        $upvoteDisabled = $hasVoted ? "disabled" : "";
        $retractVoteDisabled = $hasVoted ? "" : "disabled";
        ?>

        <div class="post-container card" style="margin-top: -20px; margin-bottom: 40px;">
            <p style="font-size: small;"><b>Uploaded:</b> <?php echo $row["datecreated"]; ?></p>
            <span class="card-title" style="font-size: smaller;"><b>Username: </b><?php echo $row["username"]; ?></span>

            <p style="font-size: smaller; display: none;"><b>Post ID:</b> <?php echo $row["postid"]; ?></p>

            <img src="<?php echo $row["image_path"]; ?>" class="card-img-top" alt="Post Image">

            <div class="card-body">

                <p class="card-text" style="margin-top: 0px; font-size:small"><b>Location:</b> <?php echo $row["location"]; ?></p>
                <p class="card-text" style="font-size: small;"><b>Description:</b> <?php echo $row["description"]; ?></p>

                <!-- Upvote and downvote -->
                <p class="card-text">Votes: <?php echo $row["votes"]; ?></p>
                <div class="row">
                    <div class="col">
                        <form action="vote.php" method="POST">
                            <input type="hidden" name="postid" value="<?php echo $postId; ?>">
                            <input type="submit" name="upvote" class="btn btn-primary" value="Vote"
                                <?php echo $upvoteDisabled; ?>>
                        </form>
                    </div>
                    <div class="col" style="margin-right: 127px;">
                        <form action="vote.php" method="POST">
                            <input type="hidden" name="postid" value="<?php echo $postId; ?>">
                            <input type="submit" name="retract_vote" class="btn btn-warning" value="Retract Vote"
                                <?php echo $retractVoteDisabled; ?>>
                        </form>
                    </div>
                </div>

                <!-- Severity level where security: is bold -->
                <p class="card-text" style="font-size: smaller;"><b>Severity and Urgency:</b>
                    <?php echo getSeverityLevel($row['votes']); ?>
                </p>

                <div class="comments-section" style="margin-top: 5px;">
                    
                    <button class="btn btn-secondary" onclick="toggleComments(<?php echo $row['postid']; ?>)">Comments</button>
                    <div id="comments-<?php echo $row['postid']; ?>" class="comments-box" style="display: none;">
                        <?php
                        $post_id = $row["postid"];
                        $comments_result = $conn->query("SELECT * FROM comments WHERE postid = $post_id ORDER BY comment_id ASC");
                        while ($comment_row = $comments_result->fetch_assoc()) {
                            echo '<p><b>' . $comment_row["username"] . ':</b> ' . $comment_row["comment"] . '</p>';
                        }
                        ?>
                        <form action="submit_comment.php" method="post">
                            <input type="hidden" name="postid" value="<?php echo $row["postid"]; ?>">
                            <input type="hidden" name="username" value="<?php echo $_SESSION["username"]; ?>">
                            <div class="form-group">
                                <label for="comment">Your Comment:</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success" style="margin-top: 5px;">Submit Comment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-- Scroll to top button -->
    <button onclick="topFunction()" id="myBtn" title="Go to top"
        class="btn btn-dark" style="position: fixed; bottom: 20px; right: 30px; z-index: 99; display: none;">Top</button>

    <script src="dark-mode.js"></script>
    <script>
        
        var mybutton = document.getElementById("myBtn");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function () {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }

        
        function toggleComments(postId) {
            var commentsBox = document.getElementById('comments-' + postId);
            if (commentsBox.style.display === 'none') {
                commentsBox.style.display = 'block';
            } else {
                commentsBox.style.display = 'none';
            }
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>




</body>

</html>
