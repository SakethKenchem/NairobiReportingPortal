<!DOCTYPE html>
<html>
<head>
  <title>Daily User Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    h1 {
      text-align: center;
    }
    #reportTable {
      margin: 20px auto;
      border-collapse: collapse;
    }
    #reportTable th, #reportTable td {
      padding: 8px;
      border: 1px solid black;
      text-align: center;
    }
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
        p{
            text-align: center;
            color : red;
        }
  </style>
</head>
<body>
<div class="navbar">
    <nav>
        <a href="adminuserView.php" class="links" style="margin-left: 5px;"><i class="fas fa-users"></i> Manage Users</a>
        <a href="adminpostsView.php" class="links"><i class="fas fa-clipboard-list"></i> Manage Posts</a>
        <a href="Error_or_Suggestion_View.php" class="links"><i class="fas fa-exclamation-triangle"></i> Error/Suggestion View</a>
        <a href="homepageviewforadmin.php" class="links"><i class="fas fa-home"></i> Homepage</a>
        <a href="daily_user_report.php" class="activeLink"><i class="fas fa-chart-bar"></i> View Daily report</a>
        <a href="admin_logout.php" class="logoutlinks"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</div>

  <h1>Daily User Report</h1>
  <p>Date and time generated: <?php date_default_timezone_set('UTC');
    date_default_timezone_set('Africa/Nairobi');
    echo (new DateTime())->format('Y-m-d H:i');?>
  </p>

  <div id="reportDisplay">
  <?php
    session_name("admin_session");
    session_start();

    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'isp';

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    if (!isset($_SESSION["admin_authenticated"]) || $_SESSION["admin_authenticated"] !== true) {
        header("Location: admin_login_signup.php");
        exit();
    }
    

    // This code will calculate the number of blocked users
    $queryBlocked = "SELECT COUNT(*) AS blocked_count FROM userlogincredentials WHERE is_blocked = 1";
    $resultBlocked = mysqli_query($conn, $queryBlocked);
    $rowBlocked = mysqli_fetch_assoc($resultBlocked);
    $blockedUserCount = $rowBlocked['blocked_count'];

    // this code will calculate the total number of users
    $queryTotalUsers = "SELECT COUNT(*) AS total_users FROM userlogincredentials";
    $resultTotalUsers = mysqli_query($conn, $queryTotalUsers);
    $rowTotalUsers = mysqli_fetch_assoc($resultTotalUsers);
    $totalUsers = $rowTotalUsers['total_users'];

    // this code will calculate the total number of posts
    $queryTotalPosts = "SELECT COUNT(*) AS total_posts FROM posts";
    $resultTotalPosts = mysqli_query($conn, $queryTotalPosts);
    $rowTotalPosts = mysqli_fetch_assoc($resultTotalPosts);
    $totalPosts = $rowTotalPosts['total_posts'];

    // Get the current date and time
    $dateGenerated = date("Y-m-d H:i:s");

    mysqli_close($conn);

    echo '<div id="reportDisplay">';
    echo '<table id="reportTable">';
    echo '<tr>';
    echo '<th>Total Users</th>';
    echo '<th>Blocked Users</th>';
    echo '<th>Total Posts</th>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>' . $totalUsers . '</td>';
    echo '<td>' . $blockedUserCount . '</td>';
    echo '<td>' . $totalPosts . '</td>';
    echo '</tr>';
    echo '</table>';
    echo '</div>';
  ?>
  </div>
  <div class="container">
    <div class="row">
        <div class="col-md-12">
            <a href="generate_daily_user_report.php" class="btn btn-primary">Download PDF</a>
        </div>
    </div>
</div>
  
</body>
</html>
