<?php
require 'connectDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentUserId = $_POST['payment_user_id'];
    $memberUserIds = $_POST['member_user_id'];
    $memberCount = count($memberUserIds);
    $registrationFeeType = $_POST['fee_type'];
    $amount = $_POST['amount'];
    $registrationTypeId = $_POST['registrationTypeId']; 
    $adminId = $_POST['adminId']; 
    
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    
    $insertPaymentQuery = "INSERT INTO registration_payment (user_id, member_count, fee_type, amount, fee_type_id,admin_id) VALUES (?, ?, ?, ?, ?,?)";
    $stmtPayment = $conn->prepare($insertPaymentQuery);
    $stmtPayment->bind_param('sissss', $paymentUserId, $memberCount, $registrationFeeType, $amount, $registrationTypeId ,$adminId);

    if ($stmtPayment->execute()) {
        
        $registrationPaymentId = $conn->insert_id;

        
        $insertDetailsQuery = "INSERT INTO registration_details (registration_payment_id, member_user_id, registration_type ,fee_type_id) VALUES (?, ?, ? ,?)";
        $stmtDetails = $conn->prepare($insertDetailsQuery);

        foreach ($memberUserIds as $memberUserId) {
            $stmtDetails->bind_param("iiss", $registrationPaymentId, $memberUserId, $registrationFeeType,$registrationTypeId);
            $stmtDetails->execute();
        }

        $stmtDetails->close();
        $stmtPayment->close();
        $conn->close();

        echo json_encode(['success' => true, 'registrationPaymentId' => $registrationPaymentId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving payment details.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
