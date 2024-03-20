<?php
require 'connectDB.php';

// Check if the connection to the database is successful
if ($mysqli->connect_error) {
    $response['success'] = false;
    $response['error'] = 'Connection failed: ' . $mysqli->connect_error;

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Get values from the AJAX request
$paymentId = $_POST['payment_id'];
$userId = $_POST['user_id'];
$expireDate = $_POST['expire_date'];
$nextInstallmentDate = $_POST['next_installment_date'];
$nextInstallmentAmount = $_POST['next_installment_amount'];

// Perform the database insertion
$query = "INSERT INTO user_payment (payment_id, user_id, installment_plan, initial_amount, next_amount, next_installment_date, payment_date) VALUES (?, ?, NULL, NULL, 0, ?, NOW())";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    $response['success'] = false;
    $response['error'] = 'Error preparing statement: ' . $mysqli->error;

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

$stmt->bind_param('iss', $paymentId, $userId, $nextInstallmentDate);

$response = array();

if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = 'Error inserting payment record: ' . $stmt->error;
}

// Close statement and connection
$stmt->close();
$mysqli->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
