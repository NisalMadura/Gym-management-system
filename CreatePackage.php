<?php
error_reporting(0);
ob_start();
session_start();

if (!isset($_SESSION['AdminId']) || !isset($_SESSION['FirstName'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SESSION['Role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Package</title>
    <link rel="stylesheet" type="text/css" href="css/createpackage.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha1256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

   <script>
        $(window).on("load resize ", function() {
            var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
            $('.tbl-header').css({'padding-right':scrollWidth});
        }).resize();

        function validateForm() {
            var membershipName = document.getElementById('membershipname').value;
            var memberCount = document.getElementById('membercount').value;
            var packagePrice = document.getElementById('packageprice').value;
            var timeDuration = document.querySelector('input[name="timeduration"]:checked');

            
            if (membershipName === '' || memberCount === '' || packagePrice === '' || timeDuration === null) {
                Swal.fire({
                    icon: 'error',
                    title: 'Check Again..',
                    text: 'Please fill in all the required fields.'
                });
                return false;
            }
            return true;
        }



    </script>
</head>
<body style="background-image: url('images/pic1.png'); background-size: cover; background-position: center;">
    <?php include 'header.php'; ?>
    <?php
    require 'connectDB.php';

    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $membership_name = $_POST['membershipname'];
        $member_count = $_POST['membercount'];
        $time_duration = $_POST['timeduration'];
        $package_price = $_POST['packageprice'];

   
        $sql = "INSERT INTO packages (membership_name, member_count, time_duration, package_price) VALUES ('$membership_name', '$member_count', '$time_duration', '$package_price')";

        if ($conn->query($sql) === TRUE) {
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "Package added successfully",
                    showConfirmButton: false,
                    timer: 3000
                }).then(function() {
                    window.location = "ExistingPackage.php"; 
                });
            </script>';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    ?>

    <main>
        <h1 class="slideInDown animated">Create Package</h1>
        <div class="form-style-5 slideInDown animated">
            <div class="alert">
                <label id="alert"></label>
            </div>
            <form method="POST" action="" id="packageadd" onsubmit="return validateForm()">
                <fieldset>
                    <legend><span class="number">1</span> Membership Name:</legend>
                    <input type="text" name="membershipname" id="membershipname" placeholder="Enter Membership Name...">
                
                    <legend><span class="number">2</span> Member Count</legend><br>
                                        <input type="number" name="membercount" id="membercount" placeholder="Enter Member Count...">
 <br>
                    <legend><span class="number">3</span> Time Duration</legend><br>
                    <input type="radio" name="timeduration" class="timeduration" value="1"> 1 Day
                    <input type="radio" name="timeduration" class="timeduration" value="30"> 1 Months
                    <input type="radio" name="timeduration" class="timeduration" value="90" checked="checked">3 Months
                    <input type="radio" name="timeduration" class="timeduration" value="180"> 6 Months
                    <input type="radio" name="timeduration" class="timeduration" value="365" checked="checked"> 12 Months
                    <br><br><br>
                    <legend><span class="number">4</span> Package Price</legend><br>
                    <input type="number" name="packageprice" id="packageprice" placeholder="Enter Package Price...">
                
                </fieldset>
                <button type="submit" name="AddPackage" class="package_add">Add New Package</button>
                <a href="ExistingPackage.php">
                <button type="button" name="ExisitingPackage" class="user_upd" style="background-color: #3498db; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; transition: background-color 0.3s ease;" onmouseover="this.style.backgroundColor='#2980b9'" onmouseout="this.style.backgroundColor='#3498db'">View Existing Package</button>
            
            </form>
        </div>
    </main>
</body>
</html>