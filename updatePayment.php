<?php
require 'connectDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'])) {
    $paymentId = $_POST['payment_id'];
    $adminId = mysqli_real_escape_string($conn, $_POST['adminId']);

   
    $updateQuery = "UPDATE members_details SET next_installment_amount = 0, next_installment_date = expiry_date WHERE payment_id = $paymentId";
   
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
       

        // Now, let's update the user_payment table
        $userId = $_POST['user_id'];
        $expireDate = $_POST['expire_date'];
        $nextInstallmentDate = $_POST['next_installment_date'];
        $nextInstallmentAmount = $_POST['next_installment_amount'];

        // Sanitize input to prevent SQL injection
        $paymentId = mysqli_real_escape_string($conn, $paymentId);
        $userId = mysqli_real_escape_string($conn, $userId);
        $expireDate = mysqli_real_escape_string($conn, $expireDate);
        $nextInstallmentDate = mysqli_real_escape_string($conn, $nextInstallmentDate);
        $nextInstallmentAmount = mysqli_real_escape_string($conn, $nextInstallmentAmount);

        $query = "INSERT INTO user_payment (payment_id, user_id, installment_plan, initial_amount, next_amount, next_installment_date, payment_date, admin_id)
        VALUES ('$paymentId', '$userId', NULL, '$nextInstallmentAmount', 0, '$expireDate', NOW(), '$adminId')
        ON DUPLICATE KEY UPDATE
        user_id = VALUES(user_id),
        next_installment_date = VALUES(next_installment_date),
        next_amount = VALUES(next_amount),   
        payment_date = NOW(),
        admin_id = VALUES(admin_id)";
        


        $insertUpdateResult = mysqli_query($conn, $query);

        if ($insertUpdateResult) {
            // Both update and insert/update in user_payment table were successful
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error updating/inserting payment record: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Error updating payment in the members_details table: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>
