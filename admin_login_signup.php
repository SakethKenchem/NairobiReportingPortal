<!DOCTYPE html>
<html>
<head>
    <title>Admin Login & Signup</title>
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
<nav class="navbar">
  <div class="container-fluid container-fluid justify-content-between">
    <a class="navbar-brand" href="#">
      <img src="Coat_of_Arms_of_Nairobi.svg.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
      Nairobi Reporting Portal
    </a>
  </div>
</nav>
    <div class="container mt-5">
        <h2 class="mb-4">Admin Dashboard</h2>
        <div id="loginForm">
            <h3>Login</h3>
            <form action="admin_login_handler.php" method="post" class="col-md-6">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" name="login" class="btn btn-primary">Login</button>
                <p>Don't have an account? <a href="javascript:void(0);" onclick="switchForm('signupForm')">Signup</a></p>
            </form>
        </div>

        <div id="signupForm" style="display: none;">
            <h3>Signup</h3>
            <form action="admin_login_handler.php" method="post" class="col-md-6">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" name="signup" class="btn btn-primary">Signup</button>
                <p>Already have an account? <a href="javascript:void(0);" onclick="switchForm('loginForm')">Login</a></p>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function switchForm(formId) {
            var loginForm = document.getElementById('loginForm');
            var signupForm = document.getElementById('signupForm');

            if (formId === 'loginForm') {
                loginForm.style.display = 'block';
                signupForm.style.display = 'none';
            } else if (formId === 'signupForm') {
                loginForm.style.display = 'none';
                signupForm.style.display = 'block';
            }
        }
    </script>
</body>
</html>
