<?php
session_name("officer_session");
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'isp';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Prevent user from accessing the page without logging in
if (!isset($_SESSION['username'])) {
    header("Location: officerlogin.html");
    exit();
}

// Function to update the complaint status
function updateStatus($complaintId, $status)
{
    global $conn;
    $updateQuery = "UPDATE complaints SET status = ? WHERE complaint_id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'ss', $status, $complaintId);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}

// Function to delete a complaint
function deleteComplaint($complaintId)
{
    global $conn;
    $deleteQuery = "DELETE FROM complaints WHERE complaint_id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($stmt, 's', $complaintId);
    $result = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}

function sendEmail($to, $subject, $message)
{
    $headers = "From: s.kenchem@gmail.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

    return mail($to, $subject, $message, $headers);
}

function getComplaintDetails($complaintId)
{
    global $conn;
    $query = "SELECT * FROM complaints WHERE complaint_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $complaintId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $complaintDetails = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    return $complaintDetails;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);

        $query = "SELECT * FROM complaints WHERE 
            LOWER(complaint_id) LIKE LOWER(?) OR
            LOWER(userid) LIKE LOWER(?) OR
            LOWER(city) LIKE LOWER(?) OR
            LOWER(name) LIKE LOWER(?) OR
            LOWER(email) LIKE LOWER(?) OR
            LOWER(id_passport) LIKE LOWER(?) OR
            LOWER(phone) LIKE LOWER(?) OR
            LOWER(address) LIKE LOWER(?) OR
            LOWER(issue_type) LIKE LOWER(?) OR
            LOWER(issue) LIKE LOWER(?) OR
            LOWER(sub_issues) LIKE LOWER(?) OR
            LOWER(if_choice_is_other) LIKE LOWER(?) OR
            LOWER(date_created) LIKE LOWER(?) OR
            LOWER(city) LIKE LOWER(?)"; 

$types = str_repeat('s', 14); 
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    $params = array_fill(0, 14, "%$search%"); 

    
    $bind_params = array(&$stmt, &$types);
    foreach ($params as &$param) {
        $bind_params[] = &$param;
    }


    call_user_func_array('mysqli_stmt_bind_param', $bind_params);

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $complaints = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
}

    } else {
  
        $query = "SELECT * FROM complaints";
        $result = mysqli_query($conn, $query);


        $complaints = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $complaints[] = $row;
        }
    }
}


// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_status']) && isset($_POST['complaint_id']) && isset($_POST['status'])) {
        $complaintId = $_POST['complaint_id'];
        $status = $_POST['status'];
        updateStatus($complaintId, $status);

       
        $complaintDetails = getComplaintDetails($complaintId);

        $to = $complaintDetails['email'];
        $subject = "Update on your complaint #" . $complaintDetails['complaint_id'];
        $message = "Hello,<br><br>Your complaint with ID #" . $complaintDetails['complaint_id'] . " has been updated.<br><br>New Status: " . $status . "<br><br>Additional Details: " . $complaintDetails['issue'] . "<br><br><b>Please do not reply to this email as it is automatically generated.<b>";

        $emailSent = sendEmail($to, $subject, $message);

        if ($emailSent) {
            echo "Email sent successfully.";
        } else {
            echo "Error sending email.";
        }
    } elseif (isset($_POST['delete_complaint']) && isset($_POST['complaint_id'])) {
        $complaintId = $_POST['complaint_id'];
        deleteComplaint($complaintId);
    }
}

function getImagePaths($complaintId)
{
    global $conn;
    $query = "SELECT file_path FROM complaint_images WHERE complaint_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $complaintId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $imagePaths = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);

    return $imagePaths;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Officer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .container {
            margin-left: 10px;
            margin-right: 35px;
            margin-bottom: 20px;
        }
        .navbar {
            background-color: #333;
            width: 1550px;
        }

.navbar a {
    color: white;
    text-align: center;
    padding: 14px 10px; 
    text-decoration: none;
    margin-right: 250px;
    margin-left: 10px;
}

.navbar a:hover {
    background-color: #ddd;
    color: black;
}

.navbar a.activeLink {
    background-color: yellow;
    color: black;
}

    </style>
</head>
<body>
<div>
    <nav class="navbar">
        <a href="officerDashboard.php" class="activeLink"><i class="fas fa-briefcase"></i> Complaints</a>
        <a href="daily_report.php"><i class="fas fa-chart-line"></i> Daily Report</a>
        <a href="homepageviewforofficer.php"><i class="fas fa-home"></i> Homepage</a>
        <a href="officerlogouthandler.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </nav>
</div>

<div class="container mt-4">
    <h1>Officer Dashboard</h1>
    <form action="officerDashboard.php" method="get" class="mb-3" >
        <div class="form-group">
            <!--<label for="search">Search Complaint or User by ID or Locality:</label>-->
            <input type="text" class="form-control" style="width: 300px;" id="search" name="search" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-danger" style="margin-top: 10px;">Search</button>
    </form>

    <table class="table table-bordered table-striped mt-4" style="font-size: small;">
        <thead>
            <tr>
                <th>Complaint ID</th>
                <th>User ID</th>
                <th>Full Name</th>
                <th>Email Address</th>
                <th>ID/Passport</th>
                <th>Phone Number</th>
                <th>Exact Address</th>
                <th>Locality</th>
                <th>Issue Type</th>
                <th>Issue</th>
                <th>Sub-Issue</th>
                <th>If sub issue is other</th>
                <th>Image(s) of Complaint</th>
                <th>Date and Time</th>
                <th>Update Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($complaints as $complaint): ?>
                <tr>
                    <td><?php echo $complaint['complaint_id']; ?></td>
                    <td><?php echo $complaint['userid']; ?></td>
                    <td><?php echo $complaint['name']; ?></td>
                    <td><?php echo $complaint['email']; ?></td>
                    <td><?php echo $complaint['id_passport']; ?></td>
                    <td><?php echo $complaint['phone']; ?></td>
                    <td><?php echo $complaint['address']; ?></td>
                    <td><?php echo $complaint['city']; ?></td>
                    <td><?php echo $complaint['issue_type']; ?></td>
                    <td><?php echo $complaint['issue']; ?></td>
                    <td><?php echo $complaint['sub_issues']; ?></td>
                    <td><?php echo $complaint['if_choice_is_other']; ?></td>

                    <td>
                    <?php
        $imagePaths = getImagePaths($complaint['complaint_id']);
        if (!empty($imagePaths)) {
            if (count($imagePaths) > 1) {
                echo '<div id="carouselExampleControls_' . $complaint["complaint_id"] . '" class="carousel slide" data-bs-ride="carousel">';
                echo '<div class="carousel-inner">';
                foreach ($imagePaths as $index => $imagePath) {
                    $activeClass = ($index === 0) ? 'active' : '';
                    echo '<div class="carousel-item ' . $activeClass . '">';
                    echo '<img src="' . $imagePath['file_path'] . '" class="d-block" style="width: 200px; height: 100px;" alt="Complaint Image">';
                    echo '</div>';
                }
                echo '</div>';
                echo '<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls_' . $complaint["complaint_id"] . '" data-bs-slide="prev">';
                echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                echo '<span class="visually-hidden">Previous</span>';
                echo '</button>';
                echo '<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls_' . $complaint["complaint_id"] . '" data-bs-slide="next">';
                echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                echo '<span class="visually-hidden">Next</span>';
                echo '</button>';
                echo '</div>';
            } else {
                // Display single image without carousel
                echo '<img src="' . $imagePaths[0]['file_path'] . '" alt="Complaint Image" width="50px" height="50px">';
            }
        } else {
            // Handle the case when there are no images for the complaint
            echo 'No images available!';
        }
        ?>
                    </td>
                    
                    
                    <td><?php echo $complaint['date_created']; ?></td>
                    <td>
                        <form action="officerDashboard.php" method="post">
                            <input type="hidden" name="complaint_id" value="<?php echo $complaint['complaint_id']; ?>">
                            <select class="form-control status-select" name="status">
                                <option value="received" <?php if ($complaint['status'] === 'received') echo 'selected'; ?>>Received</option>
                                <option value="pending" <?php if ($complaint['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                                <option value="underway" <?php if ($complaint['status'] === 'underway') echo 'selected'; ?>>Underway</option>
                                <option value="resolved" <?php if ($complaint['status'] === 'resolved') echo 'selected'; ?>>Resolved</option>
                            </select>
                            <input type="submit" name="update_status" value="Update" class="btn btn-primary btn-sm">
                        </form>
                    </td>
                    <td>
                        <form action="officerDashboard.php" method="post">
                            <input type="hidden" name="complaint_id" value="<?php echo $complaint['complaint_id']; ?>">
                            <input type="submit" name="delete_complaint" value="Delete" class="btn btn-danger btn-sm">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>
