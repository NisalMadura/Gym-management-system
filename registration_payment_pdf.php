
<?php

require 'connectDB.php';
require('fpdf/fpdf.php'); 
if (isset($_GET['payment_id'])) {
  $payment_id = $_GET['payment_id'];
  $serialized_member_ids = $_GET['member_ids'] ?? '';
  $member_ids = unserialize($serialized_member_ids);
  $installmentPlan = $_GET['installment_plan'];
  $selectedPackage = $_GET['package'];



  $sql_payment = "SELECT * FROM payments WHERE id = '$payment_id'";
  $result_payment = $conn->query($sql_payment);

  if ($result_payment->num_rows > 0) {
      $row_payment = $result_payment->fetch_assoc();

      // Retrieve payment details
      $user_id = $row_payment['user_id'];
      $package = $row_payment['package'];
      $start_date = $row_payment['start_date'];
      $expiry_date = $row_payment['expiry_date'];
      $amount = $row_payment['amount'];


    // Fetch installment plan details based on the provided installment_plan
    $sql_plan = "SELECT * FROM payment_plans WHERE installment_plan = '$installmentPlan' AND membership_name  = '$selectedPackage'";
    $result_plan = $conn->query($sql_plan);

    if ($result_plan && $result_plan->num_rows > 0) {
        $row_plan = $result_plan->fetch_assoc();

        // Retrieve installment plan details
        $installment_plan = $row_plan['installment_plan'] ?? 'Not provided';
        $initial_amount = $row_plan['initial_payment'] ?? 'Not provided';
        $next_amount = $row_plan['next_installment_amount'] ?? 'Not provided';
        $next_installment_days = $row_plan['next_installment_days'] ?? 'Not provided';

      
      // Fetch user details
      $sql_user = "SELECT username FROM users WHERE id = '$user_id'";
      $result_user = $conn->query($sql_user);
      $username = ($result_user->num_rows > 0) ? $result_user->fetch_assoc()['username'] : 'Username Not Found';

      // PDF generation code starts here
      $pdf = new FPDF();
      $pdf->AddPage();

      // Create a PDF instance
        //$pdf = new FPDF();
        $pdf = new FPDF('P','mm',array(80,297));
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);

        // Header
        $pdf->SetFillColor(0, 0, 0); 
        $pdf->Rect(0, 0, 80, 30, 'F'); 
        
        $pdf->Image('images/pic21.jpg', 30, 5, 20); 
        
        $pdf->Ln(29); 
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0);
        
        $pdf->Cell(0, 5, 'THE COLOUR FITNESS CLUB |Tel-0771231866', 0, 1, 'C');
        
        $pdf->Ln(8); 
        

        // Invoice Title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 5, 'Invoice', 0, 1, 'C');
        $pdf->Ln(8);

        $sql_created_at = "SELECT created_at FROM payments WHERE id = '$payment_id'";
        $result_created_at = $conn->query($sql_created_at);

        if ($result_created_at->num_rows > 0) {
            $row_created_at = $result_created_at->fetch_assoc();
            $created_at = $row_created_at['created_at'];
         } else {
          $created_at = date('Y-m-d H:i:s'); 
        }

        // Date and Time
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, 'Created At: ' . $created_at, 0, 1);
       


       // Payment Details
      $pdf->SetFont('Arial', '', 10);
      $pdf->Cell(0, 6, 'Receipt No: ' . $payment_id, 0, 1);

      $sql_username = "SELECT username FROM users WHERE id = '$user_id'";
      $result_username = $conn->query($sql_username);

      if ($result_username->num_rows > 0) {
         $row_username = $result_username->fetch_assoc();
         $username = $row_username['username'];

         $pdf->Cell(0, 6, 'User Payment ID: ' . $user_id . ' - ' . $username, 0, 1);
      } else {
         $pdf->Cell(0, 6, 'User Payment ID: ' . $user_id . ' - Username Not Found', 0, 1);
    }

      $pdf->Cell(0, 6, 'Package: ' . $package, 0, 1);
      $pdf->Cell(0, 7, 'Start Date: ' . $start_date, 0, 1);
      $pdf->Cell(0, 6, 'Expiry Date: ' . $expiry_date, 0, 1);

      if (!empty($member_ids)) {
        // Add a heading for member details
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 6, 'Member Details', 0, 1, 'L');
        
        // Create a table for member details
        $pdf->SetFillColor(200, 220, 255);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(0);
        $pdf->SetFont('Arial', 'B', 10);
    
        // Headers
        $pdf->Cell(15, 6, 'Mem. ID', 1, 0, 'C', 1);
        $pdf->Cell(35, 6, 'Member Name', 1, 1, 'C', 1);
    
        // Data
        foreach ($member_ids as $member_id) {
            $sql_username = "SELECT username FROM users WHERE id = '$member_id'";
            $result_username = $conn->query($sql_username);
    
            if ($result_username->num_rows > 0) {
                $row_username = $result_username->fetch_assoc();
                $username = $row_username['username'];
    
                // Add member details to the table
                $pdf->Cell(15, 6, $member_id, 1, 0, 'C');
                $pdf->Cell(35, 6, $username, 1, 1, 'C');
            }
        }
    } else {
        // Display a message if no member IDs are found
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 6, 'No Member IDs Found', 0, 1, 'C');
    }
   
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 6, 'Package Amount: Rs ' . $amount, 0, 1);

      $pdf->Cell(0, 6, 'Installment Plan: ' . $installment_plan, 0, 1);
      $pdf->Cell(0, 6, 'Initial Amount: Rs ' . $initial_amount, 0, 1);
      $pdf->Cell(0, 6, 'Next Installment Amount: Rs ' . $next_amount, 0, 1);
     

      if (!empty($start_date) && DateTime::createFromFormat('Y-m-d', $start_date) !== false) {
        $startDateTime = new DateTime($start_date);
        $startDateTime->add(new DateInterval('P' . intval($next_installment_days) . 'D'));
        $next_installment_date = $startDateTime->format('Y-m-d');

        // Display next installment details in the PDF
        $pdf->Cell(0, 7, 'Next Installment Date: ' . $next_installment_date, 0, 1);
    } else {
        $pdf->Cell(0, 7, 'Invalid start date', 0, 1);
    }
      
        
     
        $pdf->Rect(80, $pdf->GetY(), 50, 10); 
        $pdf->Cell(0, 6, '**Thank You!**', 0, 1, 'C');

        $thankYouY = $pdf->GetY() + 7; 

        // Footer
        $pdf->SetFillColor(255, 204, 204); 
        $logoX = ($pdf->GetPageWidth() - 15) / 2; 
        $pdf->Image('images/company.png', $logoX, $thankYouY + 3, 15);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(0, $thankYouY);
        $pdf->Cell(0, 7, 'Powered by Fotis Infotech|Tel-077-8582258', 0, 1,'C'); 
 
        $pdfName = 'payment_' . $payment_id . '.pdf';
        ob_clean();
        $pdf->Output('D', $pdfName); 
}
}
}
?>
