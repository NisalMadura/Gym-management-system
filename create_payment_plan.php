<?php
error_reporting(0);
ob_start();
session_start();

if (!isset($_SESSION['AdminId']) || !isset($_SESSION['FirstName'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SESSION['Role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Payment Plan</title>
    <link rel="stylesheet" type="text/css" href="css/createpaymentplan.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<style>
    
.modern-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 10px;
    font-size: 16px;
    width: 100%;
    max-width: 300px;
    cursor: pointer;
    border-radius: 5px;
    outline: none;
}


.modern-select::after {
    content: '\25BC'; 
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    pointer-events: none;
}


.modern-select:hover,
.modern-select:focus {
    border-color: #555;
}

.modern-select option {
    padding: 10px;
}
.blue-button {
  background-color: #0096FF !important;
  color: white;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.blue-button:hover {
  background-color: darkblue !important;
  color: lightblue;
}
.cancel_btn {
  background-color: gray !important;
  color: white;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.cancel_btn:hover {
  background-color: darkgray !important;
  color: lightgray;
}

</style>

</head>
<body style="background-image: url('images/pic15.jpeg'); background-size: cover; background-position: center;">
    <?php include 'header.php'; ?>
  

    <main>
        <h1 class="slideInDown animated">Payment Plan Creation</h1>
        <div class="form-style-5 slideInDown animated">
            <div class="alert">
                <label id="alert"></label>
            </div>
            <form method="POST" action="" id="paymentplanadd" onsubmit="return validateForm()">
                <fieldset>
                    <legend><span class="number">1</span> Select Membership Name:</legend>
                    <select class="modern-select" name="membership_name" id="membership_name" onchange="updatePackageAmount()">
          <option value="">Select Package</option>
          <?php
          require 'connectDB.php'; 
          $sql = "SELECT membership_name FROM packages";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
           while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['membership_name'] . '">' . $row['membership_name'] . '</option>';
        }
        } else {
          echo '<option value="">No packages available</option>';
        }
       ?>
</select>
                    <br><br>
                    <legend><span class="number">2</span> Package Amount:</legend>
                    <input type="number" name="package_amount" id="package_amount" placeholder="Enter Package Amount..."readonly>
                    <br><br>
                    <hr>
                    <legend><span class="number">3</span> Installment Plan Name:</legend>
                    <input type="text" name="installment_plan" id="installment_plan" placeholder="Enter Installment Plan Name...">
                    <br><br>
                    <legend><span class="number">4</span> Initial Payment Amount:</legend>
                    <input type="number" name="initial_payment" id="initial_payment" placeholder="Enter Initial Payment Amount...">
                    <br><br>
                    <legend><span class="number">5</span> Next Installment Amount:</legend>
   
                    <input type="text" name="first_installment_amount" id="first_installment_amount" placeholder="Balance Amount" readonly>
                    <input type="number" name="next_installment_days" id="next_installment_days" placeholder="Next Installment Days"style="width: 180px;"> Days

                
                    <br><br>
                            </fieldset>
                <button type="submit" name="AddPaymentPlan" class="paymentplan_add"onclick="addPaymentPlan()">Add Payment Plan</button>
                <button type="button" name="ViewExistingInstallmentPlan" class="blue-button" onclick="redirectToViewPlans()">View Existing Installment Plans</button>

                <button type="button" name="Cancel" class="cancel_btn" onclick="window.location.reload();"style="background-color: gray; color: white;">Cancel</button>
            </form>
        </div>
    </main>


    <script>
    function updatePackageAmount() {
        var selectedPackage = document.getElementById('membership_name').value;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'get_package.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (xhr.status === 200) {
                var packageAmount = xhr.responseText;
                document.getElementById('package_amount').value = packageAmount;
            }
        };

        xhr.send('membership_name=' + selectedPackage); 
    }

    
    // Get the input fields
    const packageAmountInput = document.getElementById('package_amount');
    const initialPaymentInput = document.getElementById('initial_payment');
    const balanceAmountInput = document.getElementById('first_installment_amount');


    initialPaymentInput.addEventListener('input', function () {
        // Get the values and calculate the balance
        const packageAmount = parseFloat(packageAmountInput.value);
        const initialPayment = parseFloat(initialPaymentInput.value);
        const balanceAmount = packageAmount - initialPayment;

        balanceAmountInput.value = isNaN(balanceAmount) ? '' : balanceAmount.toFixed(2);
    });


    function addPaymentPlan() {
    event.preventDefault(); // Prevent the default form submission

    const form = document.getElementById('paymentplanadd');
    const formData = new FormData(form);

    const nextInstallmentAmount = formData.get('first_installment_amount');
    formData.set('next_installment_amount', nextInstallmentAmount);

    fetch('add_payment_plan.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json()) // Assuming the server responds with JSON
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Payment plan added successfully!',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                form.reset();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Failed to add payment plan: ' + data.error
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

        function redirectToViewPlans() {
        window.location.href = 'viewplans.php';
    }

</script>




</body>
</html>
