<?php
require 'connectDB.php';
require('fpdf/fpdf.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentId = $_POST['payment_user_id'] ?? '';
    $memberUserIds = isset($_POST['member_user_ids']) ? $_POST['member_user_ids'] : [];
    $feeType = $_POST['fee_type'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $adminId = $_POST['admin_id'] ?? '';
    $firstName = $_POST['first_name'] ?? '';

    // If member_user_ids is not an array, convert it to an array
    if (!is_array($memberUserIds)) {
        $memberUserIds = explode(',', $memberUserIds);
    }

    $pdf = new FPDF('P','mm',array(80,297));
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

     // Header
    // $pdf->SetFillColor(0, 0, 0); 
    // $pdf->Rect(0, 0, 80, 30, 'F'); 
     
     $pdf->Image('images/pic27.jpg', 25, 5, 35); 
     
     $pdf->Ln(29); 
     
     $pdf->SetFont('Arial', 'B', 10);
     $pdf->SetTextColor(0);
     
     $pdf->Cell(0, 5, 'THE COLOUR FITNESS CLUB', 0, 1, 'C');
     $pdf->Ln(2); 

     $pdf->Cell(0, 5, 'Tel-0771231866', 0, 1, 'C');
     
     $pdf->Ln(8); 
     

     // Invoice Title
     $pdf->SetFont('Arial', 'B', 14);
     $pdf->Cell(0, 5, 'Invoice', 0, 1, 'C');
     $pdf->Ln(8);
     $pdf->SetFont('Arial', '', 10);

     $paymentQuery = "SELECT id FROM registration_payment WHERE user_id = '$paymentId'";
     $paymentResult = $conn->query($paymentQuery);

if ($paymentResult->num_rows > 0) {
    $paymentRow = $paymentResult->fetch_assoc();
    $registrationPaymentId = $paymentRow['id'];

   
    $userDetailsQuery = "SELECT u.memberno, u.username FROM users u
                         JOIN registration_payment rp ON u.id = rp.user_id
                         WHERE rp.id = '$registrationPaymentId'";
    $userDetailsResult = $conn->query($userDetailsQuery);

    if ($userDetailsResult->num_rows > 0) {
        $userDetails = $userDetailsResult->fetch_assoc();
        $memberno = $userDetails['memberno'];
        $username = $userDetails['username'];
    } else {
       
        $memberno = 'N/A';
        $username = 'N/A';
    }
} else {
   
    $registrationPaymentId = 'N/A';
    $memberno = 'N/A';
    $username = 'N/A';
}


    $pdf->Cell(0, 7, 'Receipt No: ' . $registrationPaymentId, 0, 1);
    $pdf->Cell(0, 7, 'Member No: ' . $memberno, 0, 1);
    $pdf->Cell(0, 7, 'Member Name: ' . $username, 0, 1);
    $pdf->Cell(0, 7, 'Number of Members: ' . count($memberUserIds), 0, 1);
    $pdf->Cell(0, 7, 'Fee Type: ' . $feeType, 0, 1);
    $pdf->Cell(0, 7, 'Total Amount: ' . $amount, 0, 1);


     // Create a table for member details
     $pdf->SetFillColor(200, 220, 255);
     $pdf->SetTextColor(0);
     $pdf->SetDrawColor(0);
     $pdf->SetFont('Arial', 'B', 9);

    // Table header
    $pdf->Cell(15, 6, 'M.ID', 1);
    $pdf->Cell(35, 6, 'Member Name', 1);
    $pdf->Ln();

    
    foreach ($memberUserIds as $userId) {
        
       
      $usernameQuery = "SELECT username,memberno FROM users WHERE id = '$userId'";
      $result = $conn->query($usernameQuery);

      if ($result->num_rows > 0) {
         $row = $result->fetch_assoc();
         $username = $row['username'];
         $memberno = $row['memberno'];
         } else {
          $username = 'N/A'; 
         }

        $pdf->Cell(15, 6, $memberno,1, 0, 'L');
        $pdf->Cell(35, 6, $username, 1, 1, 'L'); 
       
    }

      date_default_timezone_set('Asia/Colombo');
         $created_at = date('Y-m-d H:i:s');

        // Date and Time
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 7, 'Created At: ' . $created_at, 0, 1);

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(0, 7, 'Created By : ' . $firstName, 0, 1);

    // Output the PDF
    $pdf->Rect(80, $pdf->GetY(), 50, 10);
    $pdf->Cell(0, 7, '**Thank You!**', 0, 1, 'C');

    $thankYouY = $pdf->GetY() + 7;

    // Footer
    $pdf->SetFillColor(255, 204, 204);
    $logoX = ($pdf->GetPageWidth() - 15) / 2;
    $pdf->Image('images/company.png', $logoX, $thankYouY + 3, 15);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetXY(0, $thankYouY);
    $pdf->Cell(0, 7, 'Powered by Fotis Infotech|Tel-077-8582258', 0, 1, 'C');

    $pdfName = 'payment_' . $paymentId . '.pdf';
    ob_clean();
    $pdf->Output('D', $pdfName);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
