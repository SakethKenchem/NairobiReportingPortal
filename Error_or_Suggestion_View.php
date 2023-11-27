<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Errors/Suggestions from Users</title>
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
        .form-control{
            width: 300px;
        }
    </style>
</head>
<body>
<div class="navbar">
    <nav>
        <a href="adminuserView.php" class="links" style="margin-left: 5px;"><i class="fas fa-users"></i> Manage Users</a>
        <a href="adminpostsView.php" class="links"><i class="fas fa-clipboard-list"></i> Manage Posts</a>
        <a href="Error_or_Suggestion_View.php" class="activeLink"><i class="fas fa-exclamation-triangle"></i> Error/Suggestion View</a>
        <a href="homepageviewforadmin.php" class="links"><i class="fas fa-home"></i> Homepage</a>
        <a href="daily_user_report.php" class="links"><i class="fas fa-chart-bar"></i> View Daily report</a>
        <a href="admin_logout.php" class="logoutlinks"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</div>

    <div class="container mt-4">
        <h2>Errors/Suggestions from Users</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>User ID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                session_name("admin_session");
                session_start();
                // Database connection settings
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "isp";

                // Create a database connection
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // SQL query to retrieve reports
                $sql = "SELECT * FROM reports_to_admin";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['subject'] . "</td>";
                        echo "<td>" . $row['message'] . "</td>";
                        echo "<td>" . $row['userid'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No reports found.</td></tr>";
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
