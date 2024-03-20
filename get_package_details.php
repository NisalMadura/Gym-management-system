<?php
require 'connectDB.php'; 

if (isset($_GET['package_id'])) {
    $packageId = $_GET['package_id'];
    $sql = "SELECT * FROM packages WHERE id = $packageId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Return package details as JSON
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Package not found']);
    }
} else {
    echo json_encode(['error' => 'Package ID not provided']);
}
?>
