<?php
session_name("admin_session");
session_start();

$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'isp';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION["admin_authenticated"]) || $_SESSION["admin_authenticated"] !== true) {
    header("Location: admin_login_signup.php");
    exit();
}

function fetch_users($conn) {
    $users = array();

    $sql = "SELECT * FROM userlogincredentials";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    return $users;
}

// Function to delete a user from the userlogincredentials table
function delete_user($conn, $userId) {
    $sql = "DELETE FROM userlogincredentials WHERE userid = " . $userId;
    $conn->query($sql);
}

// Function to block/unblock a user in the userlogincredentials table
function toggle_block_user($conn, $userId, $isBlocked) {
    $isBlocked = ($isBlocked == 0) ? 1 : 0;

    $sql = "UPDATE userlogincredentials SET is_blocked = " . $isBlocked . " WHERE userid = " . $userId;
    $conn->query($sql);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];
    $userId = $_POST["user_id"];

    if ($action === "delete") {
        delete_user($conn, $userId);
    } elseif ($action === "block") {
        toggle_block_user($conn, $userId, $_POST["is_blocked"]);
    }
}

$users = fetch_users($conn);

// Code for displaying notifications
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['error'])) {
        $error_message = $_GET['error'];
        echo '<script>displayNotification("danger", "'.$error_message.'");</script>';
    }
    if (isset($_GET['suggestion'])) {
        $suggestion_message = $_GET['suggestion'];
        echo '<script>displayNotification("warning", "'.$suggestion_message.'");</script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }
        .activeLink {
            background-color: #4CAF50;
            color: white;
            margin-left: 10px;
        }
        .form-control {
            width: 300px;
        }
        .greeting-container {
        animation: slideLeftToRight 1s forwards ; /* Use the "slideLeftToRight" animation for the greeting */
        margin-top: 15px;
        margin-bottom: 19px;
        margin-left: 100px;
    }

    @keyframes slideLeftToRight {
        from {
            transform: translateX(-100%); /* Start from the left */
            opacity: 0;
        }
        to {
            transform: translateX(0); /* Move to its original position */
            opacity: 1;
        }
    }
    </style>
</head>
<body>
<div class="navbar">
    <nav>
        <a href="adminuserView.php" class="activeLink" style="margin-left: 5px;"><i class="fas fa-users"></i> Manage Users</a>
        <a href="adminpostsView.php" class="links"><i class="fas fa-clipboard-list"></i> Manage Posts</a>
        <a href="Error_or_Suggestion_View.php" class="links"><i class="fas fa-exclamation-triangle"></i> Error/Suggestion View</a>
        <a href="homepageviewforadmin.php" class="links"><i class="fas fa-home"></i> Homepage</a>
        <a href="daily_user_report.php" class="links"><i class="fas fa-chart-bar"></i> View Daily report</a>
        <a href="admin_logout.php" class="logoutlinks"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</div>
<div class="greeting-container">
<h2>Welcome, Admin</h2>
</div>
<div class="container mt-5">
    <h2 class="mb-4">Manage Users</h2>
    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <input type="text" class="form-control" id="searchValue" name="search_value" placeholder="Enter User ID or Username" style="margin-bottom: 10px;">
        </div>
        <button type="submit" class="btn btn-primary" style="margin-bottom: 15px;">Search</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>User ID</th>
                <th>Email</th>
                <th>National ID</th>
                <th>Edit Details</th>
                <th>Is Blocked?</th>
                <th>Actions</th>
                <th>View User Login Activity</th>
                <th>Date and Time account was created</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop through the user data and generate rows  -->
            <?php foreach ($users as $user) : ?>
                
                <tr>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['userid']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['national_id']; ?></td>
                    <td>
    <a href="admin_edit_user_details.php?user_id=<?php echo $user['userid']; ?>" class="btn btn-primary">Edit</a>
</td>
                    <td>
                        <?php
                            echo ($user['is_blocked'] == 1) ? "Blocked" : "Unblocked";
                        ?>
                    </td>

                    <td>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="user_id" value="<?php echo $user['userid']; ?>">
                            <button class="btn btn-danger" type="submit">Delete</button>
                        </form>

                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            
                        <input type="hidden" name="action" value="block">
                            <input type="hidden" name="user_id" value="<?php echo $user['userid']; ?>">
                            <input type="hidden" name="is_blocked" value="<?php echo $user['is_blocked']; ?>">
                            
                            <button class="btn btn-<?php echo ($user['is_blocked'] == 1) ? 'success' : 'warning'; ?>" type="submit" style="margin-top: 5px;">
                                <?php echo ($user['is_blocked'] == 1) ? 'Unblock' : 'Block'; ?>
                            </button>
                        </form>
                    </td>
                    <!-- Bootstrap Button to see full user login activity -->
                    <td>
                        <a href="login_history.php?user_id=<?php echo $user['userid']; ?>" class="btn btn-secondary">View Login History</a>
                    </td>
                    <td>
                        <?php echo $user['account_created_at']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function displayNotification(type, message) {
    const notificationContainer = document.getElementById('notification-container');
    
    // Create the notification element
    const alertElement = document.createElement('div');
    alertElement.className = `alert alert-${type} alert-dismissible fade show`;
    alertElement.role = 'alert';
    
    // Notification message
    alertElement.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Append the notification to the container
    notificationContainer.appendChild(alertElement);
    
    // Automatically dismiss the notification after a few seconds (optional)
    setTimeout(function() {
        alertElement.remove();
    }, 5000); // 5000 milliseconds (5 seconds)
}
</script>

<!-- Include Bootstrap 5.3.1 JS and any additional scripts if needed -->
<!-- Make sure to load Bootstrap JS after jQuery and Popper.js if used -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
</body>
</html>
