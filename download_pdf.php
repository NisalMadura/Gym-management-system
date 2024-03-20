<?php
require 'connectDB.php';
require('fpdf/fpdf.php');
require 'send_sms_payment.php'; 


if (isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];
    $firstName = $_GET['FirstName'];


    // Fetch payment details from the members_details table
    $sql_payment = "SELECT * FROM members_details WHERE payment_id = '$payment_id'";
    $result_payment = $conn->query($sql_payment);

    // Fetch member details from the members_details table
    $sql_member_details = "SELECT * FROM members_details WHERE payment_id = '$payment_id'";
    $result_member_details = $conn->query($sql_member_details);

    $sql_payment_user = "SELECT * FROM user_payment WHERE payment_id = '$payment_id'";
    $result_payment_user = $conn->query($sql_payment_user);
    

    if ($result_payment->num_rows > 0 && $result_payment_user->num_rows > 0) {
        $row_payment = $result_payment->fetch_assoc();
        $row_payment_user = $result_payment_user->fetch_assoc();

        // Retrieve payment details
        $start_date = $row_payment['start_date'];
        $expire_date = $row_payment['expiry_date'];
        $next_installment_amount = $row_payment_user['next_amount'];        
        $next_installment_date = $row_payment['next_installment_date'];
        

        // PDF generation code starts here
        $pdf = new FPDF('P', 'mm', array(80, 297));
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);

        // Header
        //$pdf->SetFillColor(0, 0, 0); 
        //$pdf->Rect(0, 0, 80, 30, 'F');

        $pdf->Image('images/pic27.jpg', 25, 5, 35); 

        $pdf->Ln(29);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0);

        $pdf->Cell(0, 5, 'THE COLOUR FITNESS CLUB', 0, 1, 'C');
        $pdf->Ln(2); 

     $pdf->Cell(0, 5, 'Tel-0771231866', 0, 1, 'C');
     
     $pdf->Ln(6); 

        // Invoice Title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Invoice', 0, 1, 'C');
        $pdf->Ln(6);

       
        date_default_timezone_set('Asia/Colombo');
         $created_at = date('Y-m-d H:i:s');

        // Date and Time
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, 'Created At: ' . $created_at, 0, 1);
     
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, 'Created By: ' . $firstName, 0, 1);

        // Payment Details
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, 'Receipt No: ' . $payment_id, 0, 1);

        // Fetch user details
        $sql_user = "SELECT user_id FROM members_details WHERE payment_id = '$payment_id'";
        $result_user = $conn->query($sql_user);
        $user_id = ($result_user->num_rows > 0) ? $result_user->fetch_assoc()['user_id'] : 'User ID Not Found';




        // Fetch username based on user_id
        $sql_member_info = "SELECT memberno, username FROM users WHERE id = '$user_id'";
        $result_member_info = $conn->query($sql_member_info);
        $member_info = ($result_member_info->num_rows > 0) ? $result_member_info->fetch_assoc() : null;
        
        $memberno = ($member_info !== null) ? $member_info['memberno'] : 'MemberNo Not Found';
        $username = ($member_info !== null) ? $member_info['username'] : 'Username Not Found';
        
        $pdf->Cell(0, 6, 'User Payment ID: ' . $memberno . ' - ' . $username, 0, 1);
        
        
        $pdf->Cell(0, 6, 'Start Date: ' . $start_date, 0, 1);
        $pdf->Cell(0, 6, 'Expiry Date: ' . $expire_date, 0, 1);

        // Member Details
        
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
        while ($row_member_details = $result_member_details->fetch_assoc()) {
            $member_user_id = $row_member_details['user_id'];
        
            // Fetch username and memberno based on user_id
            $sql_member_info = "SELECT memberno, username FROM users WHERE id = '$member_user_id'";
            $result_member_info = $conn->query($sql_member_info);
            $member_info = ($result_member_info->num_rows > 0) ? $result_member_info->fetch_assoc() : null;
        
            $memberno = ($member_info !== null) ? $member_info['memberno'] : 'MemberNo Not Found';
            $member_username = ($member_info !== null) ? $member_info['username'] : 'Username Not Found';
        
            // Add member details to the table
            $pdf->Cell(15, 6, $memberno, 1, 0, 'C'); 
            $pdf->Cell(35, 6, $member_username, 1, 1, 'C');
        }
        

        $pdf->SetFont('Arial', '', 10);

        $pdf->Cell(0, 6, 'Pay Amount: Rs ' . $next_installment_amount, 0, 1);
      

        if (!empty($start_date) && DateTime::createFromFormat('Y-m-d', $start_date) !== false) {
            $startDateTime = new DateTime($start_date);
            $startDateTime->add(new DateInterval('P' . intval($next_installment_days) . 'D'));
            $next_installment_date = $startDateTime->format('Y-m-d');

            // Display next installment details in the PDF
            $pdf->Cell(0, 6, 'Next Installment Date: ' . $expire_date, 0, 1);
        } else {
            $pdf->Cell(0, 8, 'Invalid start date', 0, 1);
        }

        $pdf->Rect(80, $pdf->GetY(), 50, 10);
        $pdf->Cell(0, 6, '**Thank You!**', 0, 1, 'C');

        $thankYouY = $pdf->GetY() + 4;

        // Footer 
        $pdf->SetFillColor(255, 204, 204);
        $pdf->Image('images/company.png', 32, $thankYouY + 3, 15);  // Adjust X position
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(0, $thankYouY);
        $pdf->Cell(0, 10, 'Powered by Fotis Infotech | Tel-077-8582258', 0, 1, 'C');
        
// Fetch member details from the members_details table
$sql_member_details = "SELECT * FROM members_details WHERE payment_id = '$payment_id'";
$result_member_details = $conn->query($sql_member_details);

$member_ids = [];
while ($row_member_details = $result_member_details->fetch_assoc()) {
    $member_ids[] = $row_member_details['user_id'];
}

       // SMS Sending Logic
       $message_sms = "You have successfully settled the second installment Rs:$next_installment_amount. Thank You.";
       sendSMSToMembers($member_ids, $message_sms); 


        $pdfName = 'payment_' . $payment_id . '.pdf';
        ob_clean();
        $pdf->Output('D', $pdfName);
    }
}
?>
