<?php
require 'connectDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $memberCount = $_POST['member_count'];
    $registrationFeeType = $_POST['registration_fee'];
    $amount = $_POST['amount'];
    

    // Insert into registration_payment table
    $insertPaymentQuery = "INSERT INTO registration_payment (user_id, member_count, fee_type, amount) VALUES (?, ?, ?, ?)";
    $stmtPayment = $conn->prepare($insertPaymentQuery);
    $stmtPayment->bind_param('siss', $userId, $memberCount, $registrationFeeType, $amount); // Modified line

    // Insert into registration_details table
    $insertDetailsQuery = "INSERT INTO registration_details (registration_payment_id, member_user_id, registration_type) VALUES (?, ?, ?)";
    $stmtDetails = $conn->prepare($insertDetailsQuery);

    if ($stmtPayment->execute()) {
        $registrationPaymentId = $conn->insert_id;

        // Insert details for each member
        foreach ($_POST['member_user_id'] as $memberUserId) {
            $stmtDetails->bind_param("iis", $registrationPaymentId, $memberUserId, $registrationFeeType); // Modified line
            $stmtDetails->execute();
        }

        $stmtPayment->close();
        $stmtDetails->close();
        $conn->close();

        echo json_encode(['success' => true, 'registrationPaymentId' => $registrationPaymentId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving payment details.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
