<?php 
session_start();
?>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/header.css">
</head>
<header>
<div class="header">
	<div class="logo">
		<a href="index.php">THE COLOUR FITNESS CLUB</a>
	</div>
</div>

<div class="topnav" id="myTopnav" >
	
	<a href="index.php">Users</a>
    <a href="UsersLog.php">Users Log</a>
    <a href="ManageUsers.php">Manage Users</a>
	<a href="member_registration.php">Member Registration</a>
	<a href="UserPayments.php">User Payments</a>
    <a href="CreatePackage.php">Create Package</a>
	<a href="sh.php">Create Schedule</a>
	<a href="ca.php">Photo Upload</a>
	<a href="user_profile.php">Users Profile</a>
	<a href="create_payment_plan.php">Payment Plan</a>
	<a href="logout.php" style="float: left;">Logout</a>
	

    <a href="javascript:void(0);" class="icon" onclick="navFunction()">
	  <i class="fa fa-bars"></i></a>
</div>
</header>
<script>
	function navFunction() {
	  var x = document.getElementById("myTopnav");
	  if (x.className === "topnav") {
	    x.className += " responsive";
	  } else {
	    x.className = "topnav";
	  }
	}
</script>


	

	
