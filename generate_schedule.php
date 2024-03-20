
<?php
require 'connectDB.php';
require('fpdf/fpdf.php');

class CustomPDF extends FPDF
{
    function header()
{
    // Top part
    $this->SetFillColor(255, 138, 0); // Light Orange color
    $this->Rect(0, 0, 210, 30, 'F');

    // Gym Logo
    $this->Image('images/pic21.jpg', 7, 5, 30); 

    // Big Text: No Pain No Gain
    $this->SetFont('Arial', 'B', 30);
    $this->SetTextColor(255, 255, 255); // White color
    $this->SetXY(110, 7);
    $this->Cell(0, 25, 'No Pain! No Gain!', 0, 1); 

    // Gym Name
$this->SetFont('Arial', 'B', 18);
$this->SetXY(40, 5); // Adjusted Y position
$this->SetTextColor(49, 48, 46); // Gray color
$this->Cell(0, 10, 'THE COLOUR FITNESS CLUB', 0, 1);

// Contact Number
$this->SetXY(40, 15); // Adjusted Y position
$this->SetFont('Arial', '', 12);
$this->SetTextColor(49, 48, 46); // Gray color
$this->Cell(0, 10, 'Contact: 0771231866', 0, 1);

}

    function addSlogan()
    {
        // Left part 
        $this->SetFillColor(49, 48, 46); // Dark Gray color
        $this->Rect(0, 30, 30, $this->GetPageHeight() - 0, 'F'); 
    
       
    
       
    }
    
    
    


    function generateBody($user_id,$username, $start_date, $expire_date, $weight, $height, $exercise_names, $exercise_sets)
    {
        // Body part
        $this->SetXY(45, 30);
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0, 0, 0); // Black color
        $this->Ln(10);
        // User ID
        $this->Cell(35); 

        $usernameParts = explode(' - ', $username);
        $username = end($usernameParts);

        $this->Cell(0, 10, 'User ID: ' . $user_id, 0, 1);
        $this->Ln(3);
        $this->Cell(35); 
        $this->Cell(0, 10, 'Weight: ' . $weight . ' |  Height: ' . $height, 0, 1);
        $this->Ln(2);

        // Start Date and Expire Date
        $this->Cell(35);
        $this->Cell(0, 10, 'Start Date: ' . $start_date . '   Expire Date: ' . $expire_date, 0, 1);
        $this->Ln(5);

       
       
        $tableWidth = 140; 
        $centerX = ($this->GetPageWidth() - $tableWidth) / 2;

        $this->SetXY($centerX, $this->GetY());

        // Table header for exercise data
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(52, 73, 94); // Dark Gray color
        $this->SetTextColor(0, 0, 0); // Black color
        $this->Cell(70, 10, 'Exercise', 1, 0, 'C', true);
        $this->Cell(70, 10, 'Sets', 1, 1, 'C', true);

        for ($i = 0; $i < count($exercise_names); $i++) {
            $this->SetX($centerX); // Reset X position for each row
            $this->Cell(70, 10, $exercise_names[$i], 1, 0, 'C');
            $this->Cell(70, 10, $exercise_sets[$i], 1, 1, 'C');
        }

        $this->insertUserDataIntoDatabase($user_id, $username, $weight, $height);
    }

    function addFooter()
    {
        // Footer
        $this->SetY(260); // Set Y position to the bottom of the page
        $this->SetFillColor(255, 204, 204);
        $logoX = ($this->GetPageWidth() - 15) / 2;
        $this->Image('images/company.png', $logoX, $this->GetY() + 3, 15);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Powered by Fotis Infotech | Tel-077-8582258', 0, 1, 'C');
    }
    function insertUserDataIntoDatabase($user_id, $username, $weight, $height)
    {
        
        global $conn; 

        
        $sql = "INSERT INTO user_weight (user_id, username, weight, height) VALUES ('$user_id', '$username', '$weight', '$height')";

        if ($conn->query($sql) === TRUE) {
            echo "Data inserted successfully into user_weight table.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'] ?? '';
    $username = $_POST['username'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $expire_date = $_POST['expire_date'] ?? '';
    $weight = $_POST['weight'] ?? '';
    $height = $_POST['height'] ?? '';
    $exercise_names = $_POST['exercise_name'] ?? [];
    $exercise_sets = $_POST['exercise_sets'] ?? [];

    $pdf = new CustomPDF();
    $pdf->AddPage();

    $pdf->addSlogan();

    $pdf->generateBody($user_id, $username, $start_date, $expire_date, $weight, $height, $exercise_names, $exercise_sets);
    $pdf->addFooter();

$pdfName = 'workout_schedule_' . $user_id . '.pdf';
    ob_clean();
    $pdf->Output('D', $pdfName);
} else {
    header("Location: sh.php");
    exit;
}
?>
