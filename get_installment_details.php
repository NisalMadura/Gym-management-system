<?php
require 'connectDB.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['selectedPlan']) && !empty($_GET['selectedPlan']) &&
        isset($_GET['selectedPackage']) && !empty($_GET['selectedPackage'])) {
        $selectedPlan = mysqli_real_escape_string($conn, $_GET['selectedPlan']);
        $selectedPackage = mysqli_real_escape_string($conn, $_GET['selectedPackage']);

        $sql = "SELECT initial_payment, next_installment_amount, next_installment_days 
                FROM payment_plans 
                WHERE installment_plan = ? AND membership_name  = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $selectedPlan, $selectedPackage);
        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $initialAmount = $row["initial_payment"];
            $nextInstallmentAmount = $row["next_installment_amount"];
            $nextInstallmentDays = $row["next_installment_days"];

            $response = array(
                'success' => true,
                'initialAmount' => $initialAmount,
                'nextInstallmentAmount' => $nextInstallmentAmount,
                'nextInstallmentDays' => $nextInstallmentDays  
            );

            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            $response = array('success' => false);
            header('Content-Type: application/json');
            echo json_encode($response);
        }
        $stmt->close(); 
    } else {
        $response = array('success' => false, 'message' => 'Missing or empty parameters');
        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    $response = array('success' => false, 'message' => 'Invalid request method');
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
