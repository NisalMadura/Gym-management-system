<?php
require 'connectDB.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $membershipName = $_POST['membership_name'];
    $installmentPlan = $_POST['installment_plan'];
    $initialPayment = $_POST['initial_payment'];
    $nextInstallmentAmount = $_POST['next_installment_amount'];
    $nextInstallmentDays = $_POST['next_installment_days'];

    $sql = "INSERT INTO payment_plans (membership_name, installment_plan, initial_payment, next_installment_amount, next_installment_days)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddi", $membershipName, $installmentPlan, $initialPayment, $nextInstallmentAmount, $nextInstallmentDays);
    if ($stmt->execute()) {
        // Success
        $response = array("success" => true);
        echo json_encode($response);
    } else {
        // Error
        $response = array("success" => false, "error" => "Failed to add payment plan: " . $conn->error);
        echo json_encode($response);
    }
}
?>
