<?php



if (isset($_GET['package'])) {
    $selectedPackage = $_GET['package'];

    
    require 'connectDB.php';

    
    $sqlPackage = "SELECT id,time_duration, package_price, member_count FROM packages WHERE membership_name = ?";
    $stmtPackage = $conn->prepare($sqlPackage);
    $stmtPackage->bind_param("s", $selectedPackage);
    $stmtPackage->execute();
    $resultPackage = $stmtPackage->get_result();

    
    $responseData = array();

    if ($resultPackage->num_rows > 0) {
        $rowPackage = $resultPackage->fetch_assoc();

        $responseData['package_id'] = $rowPackage['id']; // Include package ID in response

        $responseData['time_duration'] = $rowPackage['time_duration'];
        $responseData['package_price'] = $rowPackage['package_price'];
        $responseData['member_count'] = $rowPackage['member_count'];

    
        $sqlPlans = "SELECT installment_plan FROM payment_plans WHERE membership_name = '$selectedPackage'";
        $resultPlans = $conn->query($sqlPlans);

        if ($resultPlans->num_rows > 0) {
            $plans = array();

            
            while ($rowPlans = $resultPlans->fetch_assoc()) {
                $plans[] = $rowPlans['installment_plan'];
            }

            
            $responseData['installment_plans'] = $plans;
        } else {
    
            $responseData['installment_plans'] = array();
        }

        
        echo json_encode($responseData);
    } else {
        
        echo json_encode("No package found");
    }

    
    $stmtPackage->close();
    $conn->close();
}
?>
