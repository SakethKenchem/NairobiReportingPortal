<?php
session_name("admin_session");
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getSeverityLevel($votes){
    if ($votes >= 50 && $votes <= 74) {
        return "Moderate";
    } elseif ($votes >= 75 && $votes <= 134) {
        return "Serious so action recommended!";
    } elseif ($votes >= 135 && $votes <= 150) {
        return "Severe so Urgent Action Required Immediately!";
    } else {
        return "Not High Enough so not Urgent!";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] === "delete" && isset($_POST["post_id"])) {
        $post_id = $_POST["post_id"];
        // Fetch user ID associated with the deleted post
        $getUserIDQuery = "SELECT userid FROM posts WHERE postid = '$post_id'";
        $userResult = $conn->query($getUserIDQuery);
        
        if ($userResult && $userResult->num_rows > 0) {
            $userRow = $userResult->fetch_assoc();
            $userID = $userRow['userid'];
            
            // Fetch email associated with the user ID
            $getEmailQuery = "SELECT email FROM userlogincredentials WHERE userid = '$userID'";
            $emailResult = $conn->query($getEmailQuery);
            
            if ($emailResult && $emailResult->num_rows > 0) {
                $emailRow = $emailResult->fetch_assoc();
                $to = $emailRow['email'];
                
                // Send email notification
                $subject = "Post Deleted Notification";
                $message = "Your post with ID $post_id has been deleted due to violation of our terms and conditions. If you have any questions, please use the error and suggestion portal to dispute your issue.";
                $headers = "From: s.kenchem@gmail.com";
                $headers .= "Content-type: text/html\r\n";
                
                mail($to, $subject, $message, $headers);
            }
        }
        
        // Delete the post
        $deleteQuery = "DELETE FROM posts WHERE postid = '$post_id'";
        $conn->query($deleteQuery);
    }
}

$search_post_id = $_GET["search_post_id"] ?? '';

$sql = "SELECT posts.*, COUNT(comments.comment_id) AS comment_count
        FROM posts
        LEFT JOIN comments ON posts.postid = comments.postid";

if (!empty($search_post_id)) {
    $sql .= " WHERE posts.postid = '$search_post_id'";
}

$sql .= " GROUP BY posts.postid
        ORDER BY posts.datecreated DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Manage Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .navbar{
            background-color: #333;
            overflow: hidden;
        }
        .navbar a{
            float: left;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }
        .navbar a:hover{
            background-color: #ddd;
            color: black;
        }
        .activeLink{
            background-color: #4CAF50;
            color: white;
        }
        .carousel-inner{
            max-width: 400px;
            max-height: 200px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="navbar">
    <nav>
        <a href="adminuserView.php" class="links" style="margin-left: 5px;"><i class="fas fa-users"></i> Manage Users</a>
        <a href="adminpostsView.php" class="activeLink"><i class="fas fa-clipboard-list"></i> Manage Posts</a>
        <a href="Error_or_Suggestion_View.php" class="links"><i class="fas fa-exclamation-triangle"></i> Error/Suggestion View</a>
        <a href="homepageviewforadmin.php" class="links"><i class="fas fa-home"></i> Homepage</a>
        <a href="daily_user_report.php" class="links"><i class="fas fa-chart-bar"></i> View Daily report</a>
        <a href="admin_logout.php" class="logoutlinks"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</div>

<div class="container mt-5">
    <h2 class="mb-4">Manage Posts</h2>
    <h1 class="mb-4" style="font-size: smaller; margin-bottom: 2px;">Severity and Urgency Key - </h1>
    <h2 class="mb-4" style="font-size: smaller; margin-top: 1px;">Moderate = 50 - 74 votes, Serious = 75 - 134 votes, Severe = 135 - 150 votes</h2>

    <b><p class="mb-4" style="font-size: large;">Search for a Post by Post ID:</p></b>
    <form method="get" action="adminpostsView.php" class="mb-4">
        <input type="text" class="form-control" style="width: 300px;" name="search_post_id" placeholder="Enter Post ID" value="<?php echo $search_post_id; ?>"><br>
        <button class="btn btn-primary" type="submit">Search</button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Post ID</th>
                <th>User ID</th>
                <th>Image Path</th>
                <th>Description</th>
                <th>Votes</th>
                <th>Comments</th>
                <th>Username</th>
                <th>Location</th>
                <th>Date Created</th>
                <th>Severity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
function getImagePaths($postID, $conn)
{
    $getImagePathsQuery = "SELECT file_path FROM post_images WHERE post_id = '$postID'";
    $imageResult = $conn->query($getImagePathsQuery);

    $imagePaths = [];

    if ($imageResult && $imageResult->num_rows > 0) {
        while ($imageRow = $imageResult->fetch_assoc()) {
            $imagePaths[] = $imageRow['file_path'];
        }
    }

    return $imagePaths;
}
?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['postid']; ?></td>
                    <td><?php echo $row['userid']; ?></td>
                    <td>
            <?php
            // Retrieve images associated with the post from the post_images table
            $postID = $row['postid'];
            $imagePaths = getImagePaths($postID, $conn);

            if (!empty($imagePaths)) {
                if (count($imagePaths) > 1) {
                    echo '<div id="carouselExampleControls_' . $row["postid"] . '" class="carousel slide" data-bs-ride="carousel">';
                    echo '<div class="carousel-inner">';
                    foreach ($imagePaths as $index => $imagePath) {
                        $activeClass = ($index === 0) ? 'active' : '';
                        echo '<div class="carousel-item ' . $activeClass . '">';
                        echo '<img src="' . $imagePath . '" class="d-block w-100" alt="Post Image">';
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls_' . $row["postid"] . '" data-bs-slide="prev">';
                    echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                    echo '<span class="visually-hidden">Previous</span>';
                    echo '</button>';
                    echo '<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls_' . $row["postid"] . '" data-bs-slide="next">';
                    echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                    echo '<span class="visually-hidden">Next</span>';
                    echo '</button>';
                    echo '</div>';
                } else {
                    // Display single image without carousel
                    echo '<img src="' . $imagePaths[0] . '" alt="Post Image" class="img-fluid">';
                }
            } else {
                // Handle the case when there are no images for the post
                echo 'No images available!';
            }
            ?>
        </td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['votes']; ?></td>
                    <td><?php echo $row['comment_count']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['datecreated']; ?></td>
                    <td><?php echo getSeverityLevel($row['votes']); ?></td>
                    <td>
                        <form method="post" action="adminpostsView.php">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="post_id" value="<?php echo $row['postid']; ?>">
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SYF5dwKxkP9AD8NQSlqjI5g0j8me5t7I8Fb1bw" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</body>
</html>
