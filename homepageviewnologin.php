<?php
    session_start();
    // Create connection
    $servername = "localhost";
    $db_username = "root";
    $password = "";
    $dbname = "isp";
    $conn = new mysqli($servername, $db_username, $password, $dbname);

    $result = $conn->query("SELECT * FROM posts ORDER BY postid DESC");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    function getSeverityLevel($votes)
    {
        if ($votes >= 50 && $votes <= 74) {
            return '<p style="color: orange;">Moderate</p>';
        } elseif ($votes >= 75 && $votes <= 134) {
            return '<p style="color: red;">Serious so action recommended!</p>';
        } elseif ($votes >= 135 && $votes <= 150) {
            return '<b><p style="color: red;">Severe so Urgent Action Required Immediately!</p></b>';
        } else {
            return "Not High Enough so not Urgent!";
        }
    }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            background-color: #f2f2f2;
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

        .navbar {
            max-width: 99%;
            border-radius: 5px;
            background-color: #333;
        }
        .nav-link{
            color: greenyellow;
            margin-right: 20px;
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
    <a class="navbar-brand" href="#" style="margin-left: 20px;">
        <img src="Coat_of_Arms_of_Nairobi.svg.png" width="30" height="30" class="d-inline-block align-top1" alt="" >
        Nairobi Reporting Portal
    </a>
</nav>

    <h1>Welcome</h1>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
        </button>
        To vote, comment, create a post, or lodge a complaint please login<br>
        If you don't have an account, please register <a href="residentsignup.html" class="alert-link">here</a><br>
        Have an account? Login <a href="residentlogin.html" class="alert-link">here</a>.<br>
    </div>
    <h4 style="margin-top: 3px; margin-right: 1058px; margin-left: 19.7px;">Here are the latest posts:</h4>

    <?php
$postsPerPage = 3;
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$startIndex = ($currentPage - 1) * $postsPerPage;
$endIndex = $startIndex + $postsPerPage;
$totalPosts = $result->num_rows;

while ($row = $result->fetch_assoc()) {
    if ($startIndex >= 0) {
?>
        <div class="post-container card" style="margin-top: -20px; margin-bottom: 40px;">
            <p style="font-size: small;"><b>Uploaded:</b> <?php echo $row["datecreated"]; ?></p>
            <span class="card-title" style="font-size: smaller;"><b>Username: </b><?php echo $row["username"]; ?></span>

            <p style="font-size: smaller; display: none;"><b>Post ID:</b> <?php echo $row["postid"]; ?></p>

            <div id="carousel-<?php echo $row['postid']; ?>" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php
        $post_id = $row["postid"];
        $images_result = $conn->query("SELECT * FROM post_images WHERE post_id = $post_id");

        // Initialize first image as active
        $active = true;

        while ($image_row = $images_result->fetch_assoc()) {
        ?>
            <div class="carousel-item <?php echo $active ? 'active' : ''; ?>">
                <img src="<?php echo $image_row['file_path']; ?>" class="d-block w-100" alt="Post Image">
            </div>
        <?php
            $active = false;
        }
        ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?php echo $row['postid']; ?>" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?php echo $row['postid']; ?>" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
            <div class="card-body">

                <p class="card-text" style="margin-top: 0px; font-size:small"><b>Location:</b> <?php echo $row["location"]; ?></p>
                <p class="card-text" style="font-size: small;"><b>Description:</b> <?php echo $row["description"]; ?></p>

                <!-- Upvote and downvote -->
                <p class="card-text">Votes: <?php echo $row["votes"]; ?></p>
                <!--<div class="row">
                    <div class="col">
                        <form action="vote.php" method="POST">
                            <input type="hidden" name="postid" value="<?php echo $row["postid"]; ?>">
                            <?php
                            $postId = $row["postid"];
                            $hasVoted = isset($_SESSION["voted_posts"][$postId]);
                            $upvoteDisabled = $hasVoted ? "disabled" : "";
                            ?>
                            <input type="submit" name="upvote" class="btn btn-primary" value="Vote" <?php echo $upvoteDisabled; ?>>
                        </form>
                    </div>
                    <div class="col" style="margin-right: 127px;">
                        <form action="vote.php" method="POST">
                            <input type="hidden" name="postid" value="<?php echo $row["postid"]; ?>">
                            <?php
                            $retractVoteDisabled = $hasVoted ? "" : "disabled";
                            ?>
                            <input type="submit" name="retract_vote" class="btn btn-warning" value="Retract Vote" <?php echo $retractVoteDisabled; ?>>
                        </form>
                    </div>
                </div>-->

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
<?php
    }
    $startIndex--;
    if ($startIndex < 0) {
        if (--$postsPerPage == 0) break;
    }
}
?>


    <!-- Pagination -->
<?php
$postsPerPage = 5;
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$startIndex = ($currentPage - 1) * $postsPerPage;
$endIndex = $startIndex + $postsPerPage;
$totalPosts = $result->num_rows;

if ($totalPosts > 0) {
    $totalPages = ceil($totalPosts / $postsPerPage);

    // Previous Page
// Previous Page
if ($currentPage > 1) {
    echo '<nav aria-label="Page navigation example" style="margin-top: 20px;"><ul class="pagination"><li class="page-item"><a class="page-link" href="homepage.php?page=' . ($currentPage - 1) . '">Previous</a></li>';
}

// Page Numbers
for ($i = 1; $i <= $totalPages; $i++) {
    echo '<li class="page-item ' . ($i == $currentPage ? "active" : "") . '"><a class="page-link" href="homepage.php?page=' . $i . '">' . $i . '</a></li>';
}

// Next Page
if ($currentPage < $totalPages) {
    echo '<li class="page-item"><a class="page-link" href="homepage.php?page=' . ($currentPage + 1) . '">Next</a></li></ul></nav>';
} else {
    // Ensure the "Next" link is disabled on the last page  
    echo '<li class="page-item disabled"><span class="page-link">Next</span></li></ul></nav>';
}

}
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <script>
        $(".alert").alert();
    </script>
</body>
</html>