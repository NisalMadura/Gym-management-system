<?php
error_reporting(0);
ob_start();
session_start();

if (!isset($_SESSION['AdminId']) || !isset($_SESSION['FirstName'])) {
    header("Location: admin_login.php");
    exit();
}

require 'connectDB.php';

// Default query to fetch all users with Member No
$userDataQuery = "SELECT u.id, u.username, u.memberno, ph.photo
FROM users u
LEFT JOIN users_photo ph ON u.id = ph.user_id";

// Default query to fetch all payments
$paymentsQuery = "SELECT u.id, p.start_date, p.expiry_date, p.package
FROM users u
LEFT JOIN (
    SELECT p1.user_id, MAX(p1.id) AS max_id
    FROM payments p1
    GROUP BY p1.user_id
) AS latest_payments ON u.id = latest_payments.user_id
LEFT JOIN payments p ON latest_payments.max_id = p.id";

// Check if a specific user ID is provided in the URL
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['member_user_id'])) {
    $selectedUserID = $_GET['member_user_id'];

    // Modify queries to filter results by selected user ID
    $userDataQuery .= " WHERE u.id = $selectedUserID";
    $paymentsQuery .= " WHERE u.id = $selectedUserID";
} else {
    // No specific user ID provided, display all users and payments
    $userDataQuery .= " ORDER BY u.id ASC"; 
    $paymentsQuery .= " ORDER BY u.id ASC";
}

// Handle form submission for date range filter
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['startDate']) && isset($_GET['endDate'])) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];

    // Modify payments query to include date range filter
    $paymentsQuery = "SELECT u.id, p.start_date, p.expiry_date, p.package
    FROM users u
    LEFT JOIN (
        SELECT p1.user_id, MAX(p1.id) AS max_id
        FROM payments p1
        GROUP BY p1.user_id
    ) AS latest_payments ON u.id = latest_payments.user_id
    LEFT JOIN payments p ON latest_payments.max_id = p.id
    WHERE u.id = latest_payments.user_id AND p.expiry_date BETWEEN '$startDate' AND '$endDate'
    ORDER BY u.id ASC";
}

// Execute queries
$userDataResult = $conn->query($userDataQuery);
$paymentsResult = $conn->query($paymentsQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    
    <link rel="stylesheet" type="text/css" href="css/userprofile.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha1256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    
    <script>
        $(window).on("load resize ", function() {
            var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
            $('.tbl-header').css({'padding-right':scrollWidth});
        }).resize();

        function validateForm() {
            return true; 
        }
    </script>

    <style>
        .modern-input {
            padding: 8px;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            width: 150px;
            margin-bottom: 10px;
        }
        .modern-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50; 
            color: white;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
            cursor: pointer;
        }
    </style>
</head>

<body style="background-image: url('images/pic4.jpg'); background-size: cover; background-position: center;">

    <?php include 'header.php'; ?>

    <main>
        <h1 class="slideInDown animated">User Profile</h1>
        <br>
        <form id="searchForm" action="" method="GET">
            <div class="row">
                <div class="col-lg-6" align="center">
                    <label for="user_id" class="user-label" style="font-size: 16px; color: white; font-weight: bold;">Select User ID:</label>
                    <div id="memberSelectBoxes" class="select-box user-dropdown"></div>
                </div><br><br><br>
                <div class="col-lg-3" align="center">        
                    <label for="startDate" style="font-size: 16px; color: white; font-weight: bold;">From:</label>
                    <input type="date" id="startDate" name="startDate" class="modern-input">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="endDate" style="font-size: 16px; color: white; font-weight: bold;">To:</label>
                    <input type="date" id="endDate" name="endDate" class="modern-input"><br><br>
                   
                    <input type="submit" value="Search" class="modern-button">
                </div>
            </div>
        </form>
        <br><br><br>
    
        <div class="tbl-header">
            <table cellpadding="" cellspacing="0" border="0">
                <thead>
                    <tr>
                        <th>Profile Picture</th>
                        <th>User ID</th>
                        <th>Member No</th>
                        <th>User Name</th>
                        <th>Start Date</th>
                        <th>Expire Date</th>
                        <th>Package Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0">
                <tbody>
                <?php
while ($userRow = $userDataResult->fetch_assoc()) {
    $paymentRow = $paymentsResult->fetch_assoc();

    // Check if the payment date falls within the selected date range
    $expiryDate = strtotime($paymentRow['expiry_date']);
    $currentDate = strtotime(date("Y-m-d"));
    $daysDifference = ($expiryDate - $currentDate) / (60 * 60 * 24);

    $filterPasses = true;

    // Check if a date range filter is applied
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['startDate']) && isset($_GET['endDate'])) {
        $startDate = strtotime($_GET['startDate']);
        $endDate = strtotime($_GET['endDate']);
        if ($expiryDate < $startDate || $expiryDate > $endDate) {
            $filterPasses = false;
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['member_user_id'])) {
        $selectedUserID = $_GET['member_user_id'];
        if ($userRow['id'] != $selectedUserID) {
            $filterPasses = false;
        }
    }

    if ($filterPasses) {
        if ($daysDifference < 0) {
            $rowColor = "gray";
        } elseif ($daysDifference <= 7) {
            $rowColor = "red";
        } elseif ($daysDifference <= 14) {
            $rowColor = "yellow";
        } else {
            $rowColor = "green";
        }

        echo '<tr style="background-color: ' . $rowColor . ';">';

        $imagePath = $userRow['photo'];
        if (file_exists($imagePath)) {
            echo '<td><img src="' . $imagePath . '" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;"></td>';
        } else {
            echo '<td><img src="path_to_placeholder_image.jpg" alt="No Image" style="width: 50px; height: 50px;"></td>';
        }
        echo '<td>' . $userRow['id'] . '</td>';
        echo '<td>' . $userRow['memberno'] . '</td>';
        echo '<td>' . $userRow['username'] . '</td>';
        echo '<td>' . $paymentRow['start_date'] . '</td>';
        echo '<td>' . $paymentRow['expiry_date'] . '</td>';
        echo '<td>' . $paymentRow['package'] . '</td>';
        echo '<td><button onclick="viewUser(' . $userRow['id'] . ')" class="modern-button">View</button></td>';
        echo '</tr>';
    }
}
?>

                </tbody>
            </table>
        </div>
    </main>

    <script>
       $(document).ready(function() {
    createDropdown();
});

function createDropdown() {
    var selectUserDropdown = $("<select></select>");
    selectUserDropdown.attr("id", "member_user_id");
    selectUserDropdown.addClass("js-example-basic-single form-control");
    selectUserDropdown.attr("title", "Choose a user");
    selectUserDropdown.css("width", "40%");

    $("#memberSelectBoxes").append(selectUserDropdown);

    selectUserDropdown.select2({
        placeholder: 'Select User ID with Name',
        minimumInputLength: 1, 
        ajax: {
            url: 'fetchUsernames.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    username: params.term
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(user) {
                        return { id: user.id, text: user.memberno + ' - ' + user.username };
                    })
                };
            },
            cache: true
        }
    });

    selectUserDropdown.on('select2:select', function(e) {
        var selectedUserID = e.params.data.id;
        
        
        window.location.href = 'user_profile.php?member_user_id=' + selectedUserID;
    });
}


function viewUser(userID) {
    window.location.href = 'weight.php?selectedUserID=' + userID;
}
    </script>
</body>
</html>
