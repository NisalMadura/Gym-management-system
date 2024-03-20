<?php
require 'connectDB.php'; 
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    $sql = "SELECT id FROM users WHERE id = '$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        echo "exists";
    } else {
        echo "not_exists";
    }
} else {
    echo "no_user_id";
}
$conn->close();
?>
