
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Power world Gym</title>
  <link href="assets/css/googlefont.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap4.4.1.min.css">
  <link rel="stylesheet" href="assets/css/login.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>
<body style="background-image: url('images/pic15.jpeg'); background-size: cover; background-position: center;">

  <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-5">
            <img src="images/pic18.png" alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">
            <div class="card-body">
              <div class="brand-wrapper">
                <img src="images/pic19.jpg" alt="logo" class="logo">
              </div>
              <p class="login-card-description">Sign into your account</p>
                <form action="" method="post">
                  <div class="form-group">
                    <label  class="sr-only">User Name</label>
                    <input type="text" name="username" id="username" class="form-control" placeholder="User Name"  >
                  </div>
                  <div class="form-group mb-4">
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="***********" >
                  </div>
				<!--===============user login error msg ==============================================================-->                            
               <!-- PHP Logic -->
<?php
session_start();
require 'connectDB.php';

// Initialize $error variable
$error = '';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user_admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Set session variables
        $_SESSION['AdminId'] = $row['AdminId'];
        $_SESSION['FirstName'] = $row['FirstName'];
        $_SESSION['LastName'] = $row['LastName'];
        $_SESSION['IDNumber'] = $row['IDNumber'];
        $_SESSION['ContactNo'] = $row['ContactNo'];
        $_SESSION['EmailAddress'] = $row['EmailAddress'];
        $_SESSION['Role'] = $row['Role']; 

        header("Location: index.php");
        exit();
    } else {
        $error = "Incorrect username or password!";
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '$error',
                });
              </script>";
        header("Refresh: 2; url=admin_login.php"); // Redirect after 2 seconds to login page
        exit();
    }
}
?>
				<!--===============================================================================================-->				  
				  	<button class="btn btn-block login-btn mb-4" name="submit">	Login</button>	  				  				  				  
                </form>
                
                <p class="login-card-footer-text">Don't have an account? <a href="create_New_User.php" class="text-reset">Register Here</a></p>
                <nav class="login-card-footer-nav">
                
                </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>