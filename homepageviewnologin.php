<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "isp";
$conn = new mysqli($servername, $username, $password, $dbname);

// Assuming this is where you retrieve posts from the database
$result = $conn->query("SELECT * FROM posts ORDER BY postid DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>
    <!-- Favicon image -->
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
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

        .navbar {
            max-width: 99%;
            border-radius: 5px;
            background-color: green;
        }

        .carousel-inner {
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
    while ($row = $result->fetch_assoc()) {
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
            </div>
        </div>
    <?php
    }
    ?>
    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
