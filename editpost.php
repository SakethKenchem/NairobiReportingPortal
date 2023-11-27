<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container{
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
        .btn{
            margin-top: 15px;
            width: 100px;
            margin-bottom: 5px;
        }
        .form-control{
            margin-bottom: 10px;
            width: 450px;
        }
    </style>

</head>
<body>
<nav class="navbar ">
  <div class="container-fluid">
  <a class="navbar-brand" href="homepage.php" >
        <img src="Coat_of_Arms_of_Nairobi.svg.png" width="30" height="30" class="d-inline-block align-top1" alt="" style="margin-left: 10px;">
        Nairobi Reporting Portal
    </a>
  </div>
</nav>
    <div class="container mt-4">
        <h2>Edit Post</h2>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "isp";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($_GET['id'])) {
            $postid = $_GET['id'];
            $sql = "SELECT * FROM posts WHERE postid = '$postid'";
            $result = $conn->query($sql);

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                ?>
                <form method="POST" action="updatepost.php" enctype="multipart/form-data">
                    <input type="hidden" name="postid" value="<?php echo $row['postid']; ?>">
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload New Image:</label>
                        <input type="file" class="form-control" id="image" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" id="description" name="description"><?php echo $row['description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location:</label>
                        <input type="text" class="form-control" id="location" name="location" value="<?php echo $row['location']; ?>">
                    </div>

                    <!-- Add more fields as needed for editing -->
                    <button type="submit" class="btn btn-primary">Update</button>
                    <!--button to return to myposts.php-->
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='myposts.php'">Cancel</button>
                </form>
                <?php
            } else {
                echo "Post not found.";
            }
        } else {
            echo "Invalid post ID.";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
