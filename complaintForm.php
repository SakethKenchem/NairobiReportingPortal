<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://api.opencagedata.com/geocode/v1/json?key=0a8eed175ad54524ba2c5196c0ee726a"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Complaint Form</title>
    <link rel="icon" href="Coat_of_Arms_of_Nairobi.svg.png" type="image/icon type">
    <link rel="stylesheet" href="dark-mode.css" id="dark-mode-styles">

    <style>
        body {
            background-color: #fff;
            color: #000;
        }

        #imagePreviewContainer img {
            max-width: 150px;
            max-height: 150px;
            margin: 5px;
            border: 1px solid #fff;
            border-radius: 5px;
        }

        .navbar-links {
            display: flex;
            flex-direction: row;
            gap: 16px;
            font-size: large;
            color: #fff;
            margin-right: left;
            margin-top: 7px;
        }

        .navbar {
            background-color: green !important;
            padding: 10px;
            border-radius: 5px;
            max-width: 99%;
            height: 60px;
        }

        .activeLink {
            text-decoration: underline;
        }

        .navbar-container {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .container {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        echo '<div class="alert alert-danger mt-4">Please log in to view your user profile!.<a href="residentlogin.html">Click here to login</a></div>';
        exit;
    }
    if (!isset($_SESSION['userid']) || empty($_SESSION['userid'])) {
        echo '<div class="alert alert-danger mt-4">Please log in to view your user profile.<a href="residentlogin.html">Click here to login</a></div>';
        exit();
    }

    $username = $_SESSION['username'];
    //place national_id in session
    $national_id = $_SESSION['national_id'];

    ?>
    <div class="navbar-container">
        <nav class="navbar navbar-dark container-fluid justify-content-between">
            <a class="navbar-brand" href="homepage.php">
                <img src="Coat_of_Arms_of_Nairobi.svg.png" width="30" height="30" class="d-inline-block align-top1" alt="">
                Nairobi Reporting Portal
            </a>
            <div class="navbar-links">
                <a class="activeLink" href="complaintForm.php" style="color: white;">Complaint Form</a>
                <a class="nav-link" href="postCreate.php" style="color: white;">Create Post</a>
                <div class="dropdown" style="margin-top: -4px;">
                    <button class="btn btn-warning dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        User Profile
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="userprofilePage.php">Update User Details</a>
                        <a class="dropdown-item" href="Userchangepassword.php">Change Password</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="mycomplaints.php">My Complaints</a>
                        <a class="dropdown-item" href="myposts.php">My Posts</a>
                        <a class="dropdown-item" href="mycomments.php">My Comments</a>
                    </div>
                </div>
                <a class="nav-link" href="about_us.html" style="color: white;">About Us</a> <!-- Add this line -->
                <a class="nav-link" href="logout.php" style="color: white;">Logout</a>
            </div>
        </nav>
    </div>

    <div class="container mt-4, form-control border">
        <h2 class="text-center mb-4">Resident Complaint Form</h2>

        <div class="alert alert-warning alert-dismissible fade show" role="alert" id="locationWarning" style="width: 645px;"> 
            Using geolocation can provide inaccurate results. Verify the obtained address.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!--button to get geolocation from api-->
        <button type="button" class="btn btn-primary" onclick="getLocation()">Get Location</button>

        <form class="row g-3" action="process_complaint_form.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()" style="font-size:small;">
            <div class="col-md-6">
                <label for="name" class="form-label">Full Name:</label>
                <input type="text" class="form-control border" id="name" name="name" value="<?php echo $username = $_SESSION['username']; ?>">
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email Address:</label>
                <input type="email" class="form-control border" id="email" name="email" placeholder="Email Address" required>
            </div>
            <div class="col-md-6">
                <label for="ID_Passport" class="form-label">National ID or Passport Number:</label>
                <input type="text" class="form-control border" id="ID_Passport" name="ID_Passport" value="<?php echo $national_id = $_SESSION['national_id']; ?>">
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label">Phone Number:</label>
                <input type="tel" class="form-control border" id="phone" name="phone" placeholder="Phone Number" required>
            </div>
            <input type="hidden" name="userid" value="<?php echo $_SESSION['userid']; ?>">
            <div class="col-md-12">
                <label for="address" class="form-label">Exact Address:</label>
                <input type="text" class="form-control border" id="address" name="address" placeholder="Address" required>
            </div>
            <label for="exampleDataList" class="form-label">Locality:</label>
            <input class="form-control w-50" list="datalistOptions" name="city" id="exampleDataList" placeholder="Type to search for your city...">
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
            <div class="col-md-6">
                <label for="issue_type" class="form-label">Issue Type:</label>
                <select class="form-select border" id="issue_type" name="issue_type" required>
                    <option value="Roads and Related Issues">Roads and Related Issues</option>
                    <option value="Water and Sanitation">Water and Sanitation</option>
                    <option value="Solid Waste Management and Garbage">Solid Waste Management and Garbage</option>
                </select>
            </div>

            <!--select for sub issues-->
            <div class="col-md-6">
                <label for="sub_issue" class="form-label">Sub Issue:</label>
                <select class="form-select border" id="sub_issues" name="sub_issues" required>
                    <!--default option is select-->
                    <option value="Select">---Select---</option>
                    <option value="Potholes">Potholes</option>
                    <option value="Unmarked Speed Bumps">Unmarked Speed Bumps</option>
                    <option value="Poor Road Markings">Poor Road Markings</option>
                    <option value="Poor Road Signs">Poor Road Signs</option>
                    <option value="Poor Road Drainage">Poor Road Drainage</option>
                    <option value="Poor Drainage">Poor Drainage</option>
                    <option value="Poor Street Lighting">Poor Street Lighting</option>
                    <option value="Poor Road Maintenance">Poor Road Maintenance</option>
                    <option value="Poor Road Construction">Poor Road Construction</option>
                    <option value="------------------------">------------------------</option>
                    <option value="Water Shortage">Water Shortage</option>
                    <option value="Sewerage Issues">Sewerage Issues</option>
                    <option value="Water Contamination">Water Contamination</option>
                    <option value="Water Leakage">Water Leakage</option>
                    <option value="Water Pressure">Water Pressure</option>
                    <option value="Water Meter Issues">Water Meter Issues</option>
                    <option value="Water Billing Issues">Water Billing Issues</option>
                    <option value="Blocked Sewer">Blocked Sewer</option>
                    <option value="Overflowing Sewer">Overflowing Sewer</option>
                    <option value="Sewerage Smell">Sewerage Smell</option>
                    <option value="------------------------">------------------------</option>
                    <option value="Overflowing Garbage Bins">Overflowing Garbage Bins</option>
                    <option value="Garbage Collection">Garbage Collection</option>
                    <option value="Illegal Dumping">Illegal Dumping</option>
                    <option value="Garbage Burning">Garbage Burning</option>
                    <option value="Garbage Recycling">Garbage Recycling</option>
                    <option value="Garbage Sorting">Garbage Sorting</option>
                    <option value="------------------------">------------------------</option>
                    <option value="Other">Other</option>
                </select>

            </div>
            <!-- Add this div for the "Other" input field -->
            <div id="otherIssueDiv" class="col-md-6">
                <label for="if_choice_is_other" class="form-label">If Sub Issue choice is Other:</label>
                <input type="text" class="form-control border" id="if_choice_is_other" name="if_choice_is_other" placeholder="Please specify and make it concise">
            </div>

            <div class="col-12">
                <label for="issue" class="form-label">Detailed Issue Description:</label>
                <textarea class="form-control border" id="issue" name="issue" placeholder="Please describe the issue in detail" required></textarea>
            </div>

            <div class="col-12">
                <label for="imageInput" class="form-label">Images:</label>
                <input type="file" class="form-control border" id="imageInput" name="images[]" accept="image/*" multiple onchange="previewImages(event)" required multiple>
                <div id="imagePreviewContainer" class="d-flex flex-wrap"></div>
                <small id="imageSize" class="form-text text-center" style="color:red">Maximum image file size is 10MB</small><br>
                <small id="imageType" class="form-text text-center" style="color: red;">Only JPG, JPEG, PNG & GIF files are allowed</small><br>
            </div>
            <div class="col-12; text-center">
                <button type="submit" class="btn btn-primary w-50 " style="margin-bottom: 10px;">Submit</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <script>
        // Function to preview images before upload
        function previewImages(event) {
            var imagePreviewContainer = document.getElementById("imagePreviewContainer");
            imagePreviewContainer.innerHTML = "";

            var files = event.target.files;

            for (var i = 0; i < files.length; i++) {
                var imageElement = document.createElement("img");
                imageElement.classList.add("img-fluid");
                imageElement.file = files[i];
                imagePreviewContainer.appendChild(imageElement);

                var reader = new FileReader();
                reader.onload = (function(aImg) {
                    return function(e) {
                        aImg.src = e.target.result;
                    };
                })(imageElement);
                reader.readAsDataURL(files[i]);
            }
        }
    </script>
    <script>
        function validateForm() {
            var phoneNumber = document.getElementById("phone").value;
            var idNumber = document.getElementById("ID_Passport").value;
            var email = document.getElementById("email").value;
            var address = document.getElementById("address").value;
            var city = document.getElementById("exampleDataList").value;
            var issue = document.getElementById("issue").value;
            var issue_type = document.getElementById("issue_type").value;
            var sub_issues = document.getElementById("sub_issues").value;
            var otherIssue = document.getElementById("if_choice_is_other").value;

            if (phoneNumber.length != 10) {
                alert("Phone number must be 10 digits");
                return false;
            } else if (isNaN(phoneNumber)) {
                alert("Phone number must be numeric");
                return false;
            } else if (idNumber.length != 8) {
                alert("ID/Passport number must be 8 digits");
                return false;
            } else if (email.indexOf("@") == -1 || email.length < 6) {
                alert("Please enter a valid email address");
                return false;
            } else if (address.length < 5) {
                alert("Please enter a valid address");
                return false;
            } else if (city.length < 3) {
                alert("Please enter a valid city");
                return false;
            } else if (issue.length < 10) {
                alert("Please enter a valid issue");
                return false;
            } else if (issue_type.length < 3) {
                alert("Please enter a valid issue type");
                return false;
            } else if (sub_issues.length < 3) {
                alert("Please enter a valid sub issue");
                return false;
            } else if (sub_issues == "Other" && otherIssue.length < 3) {
                alert("Length of other issue must be more than at least 15 characters. No emojis allowed!");
                return false;
            }
            //prevent "select from being selected"
            else if (sub_issues == "Select") {
                alert("Please enter a valid sub issue");
                return false;
            }
            //prevent "------------------------" from being selected
            else if (sub_issues == "------------------------") {
                alert("Please enter a valid sub issue");
                return false;
            }
            /*prevent emojis from being entered in email, phonenumber, exact address, locality, issue, and other. must be done after all other validations.
            must give an alert*/
            else if (/[^\u0000-\u00ff]/.test(phoneNumber)) {
                alert("Phone number cannot contain emojis");
                return false;
            } else if (/[^\u0000-\u00ff]/.test(idNumber)) {
                alert("ID/Passport number cannot contain emojis");
                return false;
            } else if (/[^\u0000-\u00ff]/.test(email)) {
                alert("Email cannot contain emojis");
                return false;
            } else if (/[^\u0000-\u00ff]/.test(address)) {
                alert("Address cannot contain emojis");
                return false;
            } else if (/[^\u0000-\u00ff]/.test(city)) {
                alert("City cannot contain emojis");
                return false;
            } else if (/[^\u0000-\u00ff]/.test(issue)) {
                alert("Issue cannot contain emojis");
                return false;
            } else if (/[^\u0000-\u00ff]/.test(otherIssue)) {
                alert("Other issue cannot contain emojis");
                return false;
            } else {
                return true;
            }
        }
    </script>
    <script src="dark-mode.js"></script>
    <script>
        // Function to show/hide the "Other" input field
        function toggleOtherIssueInput() {
            var subIssuesDropdown = document.getElementById("sub_issues");
            var otherIssueDiv = document.getElementById("otherIssueDiv");

            if (subIssuesDropdown.value === "Other") {
                otherIssueDiv.style.display = "block";
            } else {
                otherIssueDiv.style.display = "none";
            }
        }

        // Attach the toggle function to the dropdown's change event
        var subIssuesDropdown = document.getElementById("sub_issues");
        subIssuesDropdown.addEventListener("change", toggleOtherIssueInput);

        // Call the toggle function initially to set the initial state
        toggleOtherIssueInput();
    </script>

    <script>
        // Geo location
        var x = document.getElementById("demo");

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                x.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function showPosition(position) {
            // Get the "Exact Address" input field
            var addressInput = document.getElementById("address");

            // Update the value of the "Exact Address" field with the geolocation data
            addressInput.value = "Latitude: " + position.coords.latitude +
                ", Longitude: " + position.coords.longitude;
        }
    </script>
    <script>
        function getLocation() {
            // Display the warning alert
            document.getElementById("locationWarning").style.display = "block";

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var apiKey = '0a8eed175ad54524ba2c5196c0ee726a'; // Replace with your OpenCage API key
                    var url = `https://api.opencagedata.com/geocode/v1/json?key=${apiKey}&q=${position.coords.latitude}+${position.coords.longitude}`;

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            if (data.results.length > 0) {
                                var formattedAddress = data.results[0].formatted;
                                var addressInput = document.getElementById("address");

                                addressInput.value = formattedAddress;
                            } else {
                                console.error('No results found');
                            }
                        })
                        .catch(error => console.error('Error fetching data:', error));
                });
            } else {
                console.error("Geolocation is not supported by this browser.");
            }
        }
    </script>


</body>

</html>