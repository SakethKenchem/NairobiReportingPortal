<!DOCTYPE html>
<html>
<head>
    <title>Login History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>
    <!--bootstrap button to redirect back to adminuserView.php-->
    <div class="container mt-4">
    <a href="adminuserView.php" class="btn btn-secondary">Back</a>
    </div>
    
    <?php
    session_name("admin_session");

    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "isp";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if user_id is provided in the URL
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];

        // Retrieve user information
        $user_info_sql = "SELECT username, userid FROM userlogincredentials WHERE userid = $user_id";
        $user_info_result = $conn->query($user_info_sql);

        if ($user_info_result && $user_info_result->num_rows > 0) {
            $user_info = $user_info_result->fetch_assoc();
        } else {
            // Handle the case where no user is found with the provided user_id.
            echo '<div class="alert alert-danger">User not found.</div>';
            exit();
        }

        // Retrieve login history for the specified user
        $sql = "SELECT * FROM userloginhistory WHERE userid = $user_id";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $login_history = [];
            while ($row = $result->fetch_assoc()) {
                $login_history[] = $row;
            }
        } else {
            $login_history = []; // No login history found.
        }
    } else {
        // Handle the case where no user_id is provided in the URL.
        // You can display an error message or redirect to the user management page.
        echo '<div class="alert alert-danger">User ID not provided.</div>';
        exit();
    }
    ?>

    <div class="container mt-4">
        <!-- Display user's name and user ID in the header -->
        <h2>Login History for User: <?php echo $user_info['username']; ?> (User ID: <?php echo $user_info['userid']; ?>)</h2>

        <!-- Display the login history in a Bootstrap table -->
        <?php if (!empty($login_history)) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Login Time</th>
                        <th>Logout Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($login_history as $login) : ?>
                        <tr>
                            <td><?php echo $login['login_time']; ?></td>
                            <td><?php echo $login['logout_time']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No login history found for this user.</p>
        <?php endif; ?>
    </div>

    <!-- Include Bootstrap JS (you may need to adjust the path) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+0jhF4II7pJeFgcyjzxnYGxNYeM8yZUe9PZ+q+8FqVq1TGtXj" crossorigin="anonymous"></script>
</body>
</html>
