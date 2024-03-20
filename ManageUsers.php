<?php
error_reporting(0);
ob_start();
session_start();


if (!isset($_SESSION['AdminId']) || !isset($_SESSION['FirstName'])) {
    header("Location: admin_login.php");
    exit(); 
}

require 'connectDB.php';

$sqlMaxMemberNo = "SELECT MAX(memberno) AS max_memberno FROM users";
$resultMaxMemberNo = mysqli_query($conn, $sqlMaxMemberNo);
$rowMaxMemberNo = mysqli_fetch_assoc($resultMaxMemberNo);
$currentMaxMemberNo = $rowMaxMemberNo['max_memberno'];

$MemberNo = $currentMaxMemberNo + 1;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Manage Users</title>
<link rel="stylesheet" type="text/css" href="css/manageusers.css">
<script>
  $(window).on("load resize ", function() {
    var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
    $('.tbl-header').css({'padding-right':scrollWidth});
}).resize();
</script>
<script src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha1256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous">
</script>
<script src="js/jquery-2.2.3.min.js"></script>
<script src="js/manage_users.js"></script>
<script>
  $(document).ready(function(){
  	  $.ajax({
        url: "manage_users_up.php"
		
        }).done(function(data) {
        $('#manage_users').html(data);
      });
    setInterval(function(){
      $.ajax({
        url: "manage_users_up.php"
        }).done(function(data) {
        $('#manage_users').html(data);
      });
    },5000);
  });
</script>
</head>
<body style="background-image: url('images/pic2.jpg'); background-size:cover; background-position: center;">
<?php include'header.php';?>
<main>
	<h1 class="slideInDown animated"style="color: white; font-weight: bold;">Users Manage Portal</h1>
	<div class="form-style-5 slideInDown animated">
		<div class="alert">
		<label id="alert"></label>
		</div>
		<form>
			<fieldset>
			<legend><span class="number">1</span> User Fingerprint ID:</legend>
				<label>Enter Fingerprint ID between 1 & 1000:</label>
				<input type="number" name="fingerid" id="fingerid" placeholder="User Fingerprint ID...">
				<button type="button" name="fingerid_add" class="fingerid_add">Add Fingerprint ID</button>
			</fieldset>
			<fieldset>
				<legend><span class="number">2</span> User Info</legend>
				<input type="text" name="name" id="name" placeholder="User Name...">
                <input type="text" name="idcardnumber" id="idcardnumber" placeholder="NIC Number (e.g., 1234567890)" pattern="^([0-9]{9}[x|X|v|V]|[0-9]{12})$" >
				<input type="text" name="address" id="address" placeholder="Address...">
				<label for="birthday" style="display: block; margin-bottom: 10px;">Date of Birth</label>
                <input type="date" name="birthday" id="birthday" style="display: block; width: calc(100% - 24px); padding: 12px; box-sizing: border-box;">
				<br>
				<br>
				<select name="gender" id="gender" style="display: block; width: calc(100% - 24px); padding: 12px; box-sizing: border-box;"required>
    <option value="" disabled selected>Select your gender</option>
    <option value="male">Male</option>
    <option value="female">Female</option>
    <option value="preferNotToSay">Prefer not to say</option>
</select>

<br><br>
                <input type="number" name="phonenumber" id="phonenumber" placeholder="Phone number (e.g., 1234567890)" pattern="\d{10}" required>
				<input type="number" name="whatsappno" id="whatsappno" placeholder="Whatsapp number (e.g., 1234567890)" pattern="\d{10}" required>
				<input type="number" name="userheight" id="userheight" placeholder="Height...">
				<input type="number" name="userweight" id="userweight" placeholder="Weight...">
				<label for="registerdate" style="display: block; margin-bottom: 10px;">Reg Date</label>
<input type="date" name="registerdate" id="registerdate" style="display: block; width: calc(100% - 24px); padding: 12px; box-sizing: border-box;" value="<?= date('Y-m-d') ?>" required>
               <br>
			   
                <input type="number" name="memberno" id="memberno" placeholder="Member No" required value="<?= $MemberNo ?>">
         
			   

			</fieldset>
			<button type="button" name="Add" class="user_add">Add User</button>
			<!--<button type="button" name="user_upd" class="user_upd">Update User</button>
			<button type="button" name="user_rmo" class="user_rmo">Remove User</button>-->
		</form>
	</div>

	<div class="section">
	<!--User table-->
		<div class="tbl-header slideInRight animated">
		    <table cellpadding="0" cellspacing="0" border="0">
		      <thead>
		         <tr>
				  <th>User
					 ID</th>
		          <th>Name</th>
		          <th>NIC</th>
		          <th>Address</th>
		          <th>DOB</th>
				  <th>Gender</th>
		          <th>Tel</th>
				  <th>Whatsapp No</th>
				  <th>Height</th>
		          <th>Weight</th>
		          <th>Reg 
					  Date</th>
				<th>MemNo</th>
		        </tr>
		      </thead>
		    </table>
		</div>
		<div class="tbl-content slideInRight animated">
		    <table cellpadding="0" cellspacing="0" border="0">
		      <div id="manage_users"></div>
		</div>
	</div>

</main>
</body>
</html>