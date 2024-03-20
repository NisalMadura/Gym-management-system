<?php
session_start();
date_default_timezone_set('Asia/Colombo');

if (isset($_SESSION['AdminId']) && isset($_SESSION['FirstName'])) {
    
    require 'connectDB.php'; 

    $adminId = $_SESSION['AdminId'];
    $firstName = $_SESSION['FirstName'];

    $logoutDateTime = date('Y-m-d H:i:s');

    $updateQuery = "UPDATE user_logup SET logout_datetime = ? WHERE AdminId = ? AND FirstName = ? AND logout_datetime IS NULL";

    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sss", $logoutDateTime, $adminId, $firstName);

    if ($stmt->execute()) {
        
    } else {
        
        echo "Error: " . $updateQuery . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}


$_SESSION = array();


session_destroy();

header("Location: admin_login.php");
exit();
?>
