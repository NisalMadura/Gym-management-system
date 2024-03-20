<?php


require 'connectDB.php';

session_start();


if (isset($_GET['selectedUserID'])) {
    
    $_SESSION['selectedUserID'] = $_GET['selectedUserID'];
} else {
    echo "User ID not found in URL.";
}

$userID = $_SESSION['selectedUserID'];
unset($_SESSION['selectedUserID']);


$weightDataPoints = array();
$heightDataPoints = array();
$bmiDataPoints = array();


$weightHeightSql = "SELECT weight, height, created_at FROM user_weight WHERE user_id = $userID ORDER BY created_at";
$weightHeightResult = $conn->query($weightHeightSql);

if ($weightHeightResult === false) {
    die("Weight, height, and BMI database query error: " . $conn->error);
}

if ($weightHeightResult->num_rows > 0) {
    while ($row = $weightHeightResult->fetch_assoc()) {

        $weightDataPoints[] = array("y" => (float)$row['weight'], "label" => $row['created_at']);
        $heightDataPoints[] = array("y" => (float)$row['height'], "label" => $row['created_at']);

        
        $bmi = calculateBMI($row['weight'], $row['height']);
        $bmiDataPoints[] = array("y" => $bmi, "label" => $row['created_at']);
    }
} else {
    echo "No weight, height, and BMI data found for user with ID $userID";
}


$conn->close();


function calculateBMI($weight, $height)
{
    return $weight / (($height / 100) ** 2);
}

?>

<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function () {
    // Weight Chart
    var weightChart = new CanvasJS.Chart("weightChartContainer", {
        title: {
            text: "Weight Tracker"
        },
        axisY: {
            title: "Weight"
        },
        
        data: [{
            type: "line",
            color: "red", 
            dataPoints: <?php echo json_encode($weightDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    weightChart.render();

    // Height Chart
    var heightChart = new CanvasJS.Chart("heightChartContainer", {
        title: {
            text: "Height Tracker"
        },
        axisY: {
            title: "Height"
        },
        data: [{
            type: "line",
            color: "orange",
            dataPoints: <?php echo json_encode($heightDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    heightChart.render();

   
    
}
</script>

<style>

  

body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.chart-container {
    max-width: 800px;
    margin: 20px auto;
}


#weightChartContainer {
    height: 370px;
    width: 80%;
    margin: 20px auto;
    background-color: #f9f9f9; 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}


#heightChartContainer {
    height: 370px;
    width: 80%;
    margin: 20px auto;
    background-color: #f9f9f9; 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

#userDetailsContainer {
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

#userDetails {
    max-width: 600px;
    margin: 0 auto;
    color: #333;
}

#userDetails p {
    margin: 10px 0;
    font-size: 16px;
}

#userDetails h2 {
    color: #333;
    border-bottom: 2px solid #ddd;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

#bmiChartContainer {
    height: 370px;
    width: 80%;
    margin: 20px auto;
    background-color: #f9f9f9; 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}
</style>
</head>
<?php include'header.php';?>

<body style="background-image: url('images/pic22.jpg'); background-size: 160%; background-position: center;">

<div id="userDetailsContainer" class="chart-container" style="background-color: #f0f0f0;">
<h2 style="color: #333; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; text-align: center;">User Details</h2>
    <div id="userDetails">
        <?php
        require 'connectDB.php';
        $userDetailsSql = "SELECT * FROM users WHERE id = $userID";
        $userDetailsResult = $conn->query($userDetailsSql);

        if ($userDetailsResult === false) {
            die("User details database query error: " . $conn->error);
        }

        if ($userDetailsResult->num_rows > 0) {
            $userDetails = $userDetailsResult->fetch_assoc();
            

            echo "<p><strong>ID:</strong> {$userDetails['id']}</p>";
            echo "<p><strong>Username:</strong> {$userDetails['username']}</p>";
            echo "<p><strong>ID Card Number:</strong> {$userDetails['idcardnumber']}</p>";
            echo "<p><strong>User Address:</strong> {$userDetails['useraddress']}</p>";
            echo "<p><strong>Birthday:</strong> {$userDetails['birthday']}</p>";
            echo "<p><strong>Phone Number:</strong> {$userDetails['phonenumber']}</p>";
            echo "<p><strong>Register Date:</strong> {$userDetails['registerdate']}</p>";
        
        
        } else {
            echo "User details not found.";
        }
        ?>
    </div>
</div>

<div id="weightChartContainer" style="height: 370px; width: 80%;"></div>
<div id="heightChartContainer" style="height: 370px; width: 80%;"></div>



<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

</body>

</html>
