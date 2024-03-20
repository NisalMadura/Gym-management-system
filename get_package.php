<?php
// get_package.php

// Include your database connection script
require 'connectDB.php';

// Function to fetch package price based on the selected package name
function fetchPackagePrice($selectedPackage) {
    global $conn;

    // Query your database to get the package amount based on the selected package
    $sql = "SELECT package_price FROM packages WHERE membership_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedPackage);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['package_price'];
    } else {
        echo "Package not found";
    }
}

// Check if the package_name is received either through GET or POST
if (isset($_REQUEST['membership_name'])) {
    $selectedPackage = $_REQUEST['membership_name'];
    fetchPackagePrice($selectedPackage);
} else {
    echo "Invalid request";
}
?>
