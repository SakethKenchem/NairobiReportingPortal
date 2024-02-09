<?php
session_name("admin_session");
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";
$conn = new mysqli($servername, $username, $password, $dbname);

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
    <title>Homepage for Admin</title>
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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

        .navbar {
            max-width: 99%;
            border-radius: 5px;
            background-color: #333;
        }

        .nav-link {
            color: greenyellow;
            margin-right: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark container-fluid justify-content-between">
        <a class="navbar-brand" href="#" style="margin-left: 20px;">
            <img src="Coat_of_Arms_of_Nairobi.svg.png" width="30" height="30" class="d-inline-block align-top1" alt="">
            Nairobi Reporting Portal
        </a>
        </div>
        <a class="nav-link" href="adminpostsView.php">Dashboard</a>
        </div>
    </nav>

    <h1>Welcome, Admin</h1>
    <h4>Here are the latest posts:</h4>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="post-container card">
            <p style="font-size: smaller;"><b>Upload Datetime:</b> <?php echo $row["datecreated"]; ?></p>

            <p style="font-size: smaller;"><b>Post ID:</b> <?php echo $row["postid"]; ?></p>

            <img src="<?php echo $row["image_path"]; ?>" class="card-img-top" alt="Post Image">

            <div class="card-body">
                <h5 class="card-title" style="font-size: smaller;"><b>Username: </b><?php echo $row["username"]; ?></h5>
                <p class="card-text"><b>Location:</b> <?php echo $row["location"]; ?></p>
                <p class="card-text"><b>Description:</b> <?php echo $row["description"]; ?></p>
                <p class="card-text">Votes: <?php echo $row["votes"]; ?></p>

                <!--severity level where security: is bold-->
                <p class="card-text"><b>Severity and Urgency:</b> <?php echo getSeverityLevel($row['votes']); ?></p>

                <div class="comments-section">
                    <h6>Comments:</h6>
                    <?php
                    // this code will display all the comments for the post from the database
                    $post_id = $row["postid"];
                    $comments_result = $conn->query("SELECT * FROM comments WHERE postid = $post_id ORDER BY comment_id ASC");

                    while ($comment_row = $comments_result->fetch_assoc()) {
                        echo '<p><b>' . $comment_row["username"] . ':</b> ' . $comment_row["comment"] . '</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>