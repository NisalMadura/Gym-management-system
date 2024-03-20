<?php
require 'connectDB.php'; 

$sql = "SELECT * FROM payment_plans";
$result = $conn->query($sql);

$plans = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $plans[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Payment Plans</title>
    
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

header, footer {
}

main {
    padding: 20px;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

.plans-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
}

.plan-card {
    width: 300px;
    background-color: #fff;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    position: relative;
}

.plan-card.plan-basic {
    background-color: #ffcccb; 
}

.plan-card.plan-premium {
    background-color: #b0e0e6; 
}


  .delete-button {
    position: absolute;
    top: 5px;
    right: 5px;
    cursor: pointer;
    color: #fff;
    background-color: #ff0000;
    padding: 5px;
    border-radius: 5px;
  }


</style>
</head>
<body style="background-image: url('images/pic17.jpg'); background-size: cover; background-position: center;">
<?php include 'header.php'; ?>
    <main>
        <h1 style="color: white; font-weight: bold;">Existing Payment Plans</h1>
        <div class="plans-container">
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const plansData = <?php echo json_encode($plans); ?>;

    const plansContainer = document.querySelector('.plans-container');

    const cardColors = ['#ffcccb', '#b0e0e6', '#ffd700', '#90ee90', '#add8e6', '#ffb6c1', '#dda0dd', '#f0e68c', '#87cefa', '#fa8072', '#20b2aa', '#e9967a', '#808000', '#7fffd4', '#6495ed', '#ff7f50', '#48d1cc', '#c71585', '#ff6347', '#40e0d0', '#ff4500', '#6a5acd', '#d2b48c', '#4682b4', '#f08080', '#8a2be2', '#556b2f', '#2e8b57', '#ba55d3', '#deb887', '#5f9ea0', '#008080'];

    plansData.forEach((plan, index) => {
        const planCard = document.createElement('div');
        planCard.classList.add('plan-card');
        planCard.style.backgroundColor = cardColors[index % cardColors.length];
        planCard.classList.add(`plan-${plan.membership_name.toLowerCase().replace(/\s/g, '-')}`);

        planCard.innerHTML = `
            <div class="plan-details">
                <h2 style="text-align: center; color: Black;">${plan.membership_name}</h2>
                <p style="color: Black;">Installment Plan: ${plan.installment_plan}</p>
                <p style="color: Black;">Initial Payment: ${plan.initial_payment}</p>
                <p style="color: Black;">Next Installment Amount: ${plan.next_installment_amount}</p>
                <p style="color: Black;">Next Payment Days: ${plan.next_installment_days}</p>
                <p style="color: Black;">Created Date: ${plan.created_at}</p>
            </div>
            </div>
  <div class="delete-button" onclick="deletePlan(${plan.id})">Delete</div>
        `;

        plansContainer.appendChild(planCard);
    });

  

});

function deletePlan(planId) {
   
            window.location.href = `delete_plan.php?id=${planId}`;
    
}


    </script>
    
</body>
</html>
