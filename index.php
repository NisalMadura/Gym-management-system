<?php
error_reporting(0);
ob_start();
date_default_timezone_set('Asia/Colombo');
session_start();


if (!isset($_SESSION['AdminId']) || !isset($_SESSION['FirstName'])) {
    header("Location: admin_login.php");
    exit(); 
}


require 'connectDB.php'; 


$adminId = $_SESSION['AdminId'];
$firstName = $_SESSION['FirstName'];

$currentDateTime = date('Y-m-d H:i:s');


$insertQuery = "INSERT INTO user_logup (AdminId, FirstName, login_datetime) VALUES (?, ?, ?)";

$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("sss", $adminId, $firstName, $currentDateTime);

if ($stmt->execute()) {
   
} else {
    
    echo "Error: " . $insertQuery . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>



<!DOCTYPE html>
<html>
<head>
  <title>Users</title>
<link rel="stylesheet" type="text/css" href="css/Users.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
  $(window).on("load resize ", function() {
    var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
    $('.tbl-header').css({'padding-right':scrollWidth});
}).resize();
</script>
</head>
<body style="background-image: url('images/pic6.jpg'); background-size: 120%; background-position: center;">
<?php include'header.php'; ?> 
<main>
  <section>
  <!--User table-->
  <h1 class="slideInDown animated"style="color: white; font-weight: bold;">All Users details</h1>
  <div class="tbl-header slideInRight animated">
    <table cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th>User
		      ID </th>
          <th>Name </th>
          <th>NIC</th>
          <th>Address</th>
          <th>Birthday</th>
          <th>Gender</th>
          <th>TEL | Mobile</th>
          <th>Whatsapp No</th>
          <th>Height</th>
          <th>Weight</th>
          <th>Register Date</th>
          <th>Member No</th>
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content slideInRight animated">
    <table cellpadding="0" cellspacing="0" border="0">
      <tbody>
        <?php
          //Connect to database
          require'connectDB.php';

            $sql = "SELECT * FROM users WHERE NOT username='' ORDER BY id DESC";
            $result = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($result, $sql)) {
                echo '<p class="error">SQL Error</p>';
            }
            else{
              mysqli_stmt_execute($result);
                $resultl = mysqli_stmt_get_result($result);
              if (mysqli_num_rows($resultl) > 0){
                  while ($row = mysqli_fetch_assoc($resultl)){
          ?>
                      <TR>
                      <TD><?php echo $row['id'];?></TD> 
					            <TD><?php echo $row['username'];?></TD>
                      <TD><?php echo $row['idcardnumber'];?></TD>
                      <TD><?php echo $row['useraddress'];?></TD>
                      <TD><?php echo $row['birthday'];?></TD>
                      <TD><?php echo $row['gender'];?></TD>
                      <TD><?php echo $row['phonenumber'];?></TD>
                      <TD><?php echo $row['whatsappno'];?></TD>
                      <TD><?php echo $row['userheight'];?></TD>
                      <TD><?php echo $row['userweight'];?></TD>
                      <TD><?php echo $row['registerdate'];?></TD>
                      <TD><?php echo $row['memberno'];?></TD>

                      </TR>
        <?php
                }   
            }
          }
        ?>
      </tbody>
    </table>
  </div>
</section>
</main>
</body>
</html>



