<?php
// Retrieve the selected package from the AJAX request
if(isset($_GET['package'])) {
    $selectedPackage = $_GET['package'];

    require'connectDB.php';
    $sql = "SELECT time_duration, package_price,member_count FROM packages WHERE membership_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedPackage);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Return the data as JSON
        $responseData = array(
            'time_duration' => $row['time_duration'],
            'package_price' => $row['package_price'],
            'member_count' => $row['member_count']

        );
        echo json_encode($responseData);
    } else {
        echo json_encode("No package found");
    }

    $stmt->close();
    $conn->close();
}
?>
