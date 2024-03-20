<?php
// Include your database connection logic here
require 'connectDB.php';

if (isset($_GET['registrationFeeType'])) {
    $registrationFeeType = $_GET['registrationFeeType'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, fee_amount FROM registrationfee_type WHERE registrationFeeType = ?");
    $stmt->bind_param("s", $registrationFeeType);
    $stmt->execute();
    $stmt->bind_result($id, $feeAmount);

    if ($stmt->fetch()) {
        // Return the fee amount and id as JSON
        echo json_encode(array('success' => true, 'feeAmount' => $feeAmount, 'id' => $id));
    } else {
        // If the fee type is not found, return an error
        echo json_encode(array('success' => false, 'message' => 'Fee type not found'));
    }

    $stmt->close();
} else {
    // If registrationFeeType is not set in the GET parameters, return an error
    echo json_encode(array('success' => false, 'message' => 'Registration fee type not provided'));
}

// Close the database connection
$conn->close();
?>
