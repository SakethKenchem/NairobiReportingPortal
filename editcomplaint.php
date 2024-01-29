<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Complaint</title>
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-label {
            font-weight: bold;
        }

        .form-control {
            margin-bottom: 10px;
        }

        .btn-primary {
            margin-top: 20px;
        }
        .navbar{
            background-color: #f8f9fa;
            margin-right: 1000px;
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
<?php
    session_start();
    if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
        echo '<div class="alert alert-danger mt-4">Please log in to edit complaints.</div>';
        exit();
    }

    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo '<div class="alert alert-danger mt-4">Invalid complaint ID.</div>';
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "isp";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the complaint ID from the URL parameter
    $complaintId = $_GET['id'];

    $sql = "SELECT * FROM complaints WHERE complaint_id = '$complaintId'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $complaint = $result->fetch_assoc();
?>
        <div class="container mt-4">
            <h2 class="text-center">Edit Complaint</h2>
            <form action="updateComplaint.php" method="POST" onsubmit="return validateForm();">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $complaint['name']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $complaint['email']; ?>">
                </div>
                <div class="mb-3">
                    <label for="ID_Passport" class="form-label">National ID or Passport Number:</label>
                    <input type="text" class="form-control" id="ID_Passport" name="ID_Passport" value="<?php echo $complaint['id_passport']; ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number:</label>
                    <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $complaint['phone']; ?>">
                </div>
                <input type="hidden" name="complaintId" value="<?php echo $complaint['complaint_id']; ?>">
                <div class="mb-3">
                    <label for="address" class="form-label">Exact Address:</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $complaint['address']; ?>">
                </div>
                <div class="mb-3">
                    <label for="exampleDataList" class="form-label">Locality:</label>
                    <input class="form-control" list="datalistOptions" name="city" id="exampleDataList" value="<?php echo $complaint['city']; ?>">
                    <datalist id="datalistOptions">
    <option value="Eastleigh">
    <option value="Embakasi">
    <option value="Githurai">
    <option value="Karen">
    <option value="Kahawa">
    <option value="Kasarani">
    <option value="Kawangware">
    <option value="Kayole">
    <option value="Kibera">
    <option value="Kileleshwa">
    <option value="Kilimani">
    <option value="Langata">
    <option value="Lavington">
    <option value="Madaraka">
    <option value="Mathare">
    <option value="Mihango">
    <option value="Mlolongo">
    <option value="Mombasa Road">
    <option value="Muthaiga">
    <option value="Ngong">
    <option value="Ngundu">
    <option value="Nairobi West">
    <option value="Ongata Rongai">
    <option value="Parklands">
    <option value="Rongai">
    <option value="Ruai">
    <option value="Runda">
    <option value="Ruaka">
    <option value="South B">
    <option value="South C">
    <option value="Thika Road">
    <option value="Upper Hill">
    <option value="Waiyaki Way">
    <option value="Westlands">
</datalist>
                </div>
                <div class="mb-3">
                    <label for="issue_type" class="form-label">Issue Type:</label>
                    <select class="form-select" id="issue_type" name="issue_type">
                        <option value="Roads and Related Issues">Roads and Related Issues</option>
                        <option value="Water and Sanitation">Water and Sanitation</option>
                        <option value="Solid Waste Management and Garbage">Solid Waste Management and Garbage</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="issue" class="form-label">Issue:</label>
                    <textarea class="form-control" id="issue" name="issue"><?php echo $complaint['issue']; ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="imageInput" class="form-label">Images:</label>
                    <input type="file" class="form-control" id="imageInput" name="images" accept="image/*" multiple onchange="previewImages(event)">
                    <div id="imagePreviewContainer" class="d-flex flex-wrap"></div>
                    <small id="imageSize" class="form-text text-center" style="color:red">Maximum image size is 5MB</small>
                    <small id="imageType" class="form-text text-center" style="color: red;">Only JPG, JPEG, PNG & GIF files are allowed</small>
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
                <div class="text-center">
                    <a href="mycomplaints.php" class="btn btn-secondary" style="margin-top: 5px;">Cancel Edit</a>
                </div> 
                <!-- Add this hidden input field after the form tag -->
<input type="hidden" name="existingImages" value="<?php echo implode(',', $complaint['image_path']); ?>">

            </form>
        </div>
<?php
    } else {
        echo '<div class="alert alert-danger mt-4">Complaint not found.</div>';
    }


    $conn->close();
?>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script> 

    function validateForm(){
        var phoneNumber = document.getElementById("phone").value;
        var idNumber = document.getElementById("ID_Passport").value;
        var email = document.getElementById("email").value;
        var address = document.getElementById("address").value;
        var city = document.getElementById("exampleDataList").value;
        var issue = document.getElementById("issue").value;
        var issue_type = document.getElementById("issue_type").value;

        if (phoneNumber.length != 10){
            alert("Phone number must be 10 digits");
            return false;
        }
        else if (isNaN(phoneNumber)){
            alert("Phone number must be numeric");
            return false;
        }
        else if (idNumber.length != 8){
            alert("ID/Passport number must be 8 digits");
            return false;
        }
        else if (email.indexOf("@") == -1 || email.length < 6){
            alert("Please enter a valid email address");
            return false;
        }
        else if (address.length < 5){
            alert("Please enter a valid address");
            return false;
        }
        else if (city.length < 3){
            alert("Please enter a valid city");
            return false;
        }
        else if (issue.length < 10){
            alert("Please enter a valid issue");
            return false;
        }
        else if (issue_type.length < 3){
            alert("Please enter a valid issue type");
            return false;
        }
        else{
            return true;
        }
    }
</script>
</html>
