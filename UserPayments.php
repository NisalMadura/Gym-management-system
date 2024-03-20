<?php
error_reporting(0);
ob_start();
session_start();


if (!isset($_SESSION['AdminId']) || !isset($_SESSION['FirstName'])) {

    header("Location: admin_login.php");
    exit(); 
}
?>
<!DOCTYPE html>
<html>
<head>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
  $(window).on("load resize ", function() {
    var scrollWidth = $('.payment-card').width() - $('.payment-card').width();
    $('.tbl-header').css({'padding-right':scrollWidth});
}).resize();
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

  <title>User Payment Portal</title>
  <link rel="stylesheet" type="text/css" href="css/payment_styles.css">
  <style>



    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      
  
    }
    
    .payment-container {
      display: flex;
      justify-content: center;
      align-items: center;
      
    }
    .payment-card {
      background-color: #ffffff;
      border-radius: 10px;
      padding: 50px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      text-align: center;
      width: 50%; 
      height:100%; ;
    }
    .payment-card label,
    .payment-card select,
    .payment-card input[type="text"],
    .payment-card input[type="date"] {
      display: block;
      margin-bottom: 10px;
      text-align: left;
      width: calc(100% - 24px);
      padding: 12px;
      box-sizing: border-box;
    }
    .payment-card button {
      padding: 15px;
      margin-top: 20px;
      width: 45%;
      border-radius: 5px;
      cursor: pointer;
    }
    .payment-card .back-button {
      float: left;
    }
    .payment-card .pay-now-button {
      float: right;
      background-color: #3498db;
      color: #fff;
      border: none;
    }
    .payment-card .pay-now-button:hover {
      background-color: #2980b9;
    }
    label {
  font-family: Arial, sans-serif;
  font-size: 16px;
  color: #333;
  margin-bottom: 8px; 
}


input[type="text"],
input[type="date"] {
  font-family: Arial, sans-serif;
  font-size: 14px;
  color: #555;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  background-color: #f8f8f8;

}
.card-details {
    list-style: none;
    counter-reset: form-counter; 
    padding-left: 0;
}


.card-details label::before {
    counter-increment: form-counter; 
    content: counter(form-counter); 
    width: 20px; 
    display: inline-block;
    text-align: center;
    margin-right: 10px; 
    font-weight: bold; 
}

.card-details label::before {
    counter-increment: form-counter; 
    content: counter(form-counter); 
    width: 20px; 
    display: inline-block;
    text-align: center;
    margin-right: 10px; 
    font-weight: bold; 
    border-radius: 50%; 
    background-color: #fff; 
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    padding: 5px; 
}



.card-details {
    max-height: 570px; 
    overflow-y: auto; 
}


  </style>
</head>
<?php include'header.php';?>
<body style="background-image: url('images/pic2.png'); background-size: cover; background-position: center;">
<?php
require 'connectDB.php';
require('fpdf/fpdf.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $package = $_POST['package'];
    $start_date = $_POST['start_date'];
    $expiry_date = $_POST['expiry_date'];
    $amount = $_POST['amount'];
    $member_ids = $_POST['member_user_id'];

     
     $package_query = "SELECT id FROM packages WHERE membership_name = '$package'";
     $package_result = $conn->query($package_query);
     
     if ($package_result->num_rows > 0) {
         $package_row = $package_result->fetch_assoc();
         $package_id = $package_row['id'];
     } else {
         
     }
 
     // Insert payment details into the database
     $sql = "INSERT INTO payments (user_id, package_id, package, start_date, expiry_date, amount) 
             VALUES ('$user_id', '$package_id', '$package', '$start_date', '$expiry_date', '$amount')";
 
    if ($conn->query($sql) === TRUE) {
        $payment_id = $conn->insert_id;

        if (is_array($member_ids)) {
            foreach ($member_ids as $member_id) {
                $insertMemberQuery = "INSERT INTO payments (user_id, package_id, package, start_date, expiry_date, amount) 
                VALUES ('$user_id', '$package_id', '$package', '$start_date', '$expiry_date', '$amount')";
                $conn->query($insertMemberQuery);
                $serialized_member_ids = serialize($member_ids);
            }
        }
        $adminId = $_SESSION['AdminId'];
        
        $installment_plan = isset($_POST['installment_plan']) ? $_POST['installment_plan'] : '';
        $initial_amount = isset($_POST['initial_amount']) ? $_POST['initial_amount'] : '';
        $next_amount = isset($_POST['next_amount']) ? $_POST['next_amount'] : '';
        $next_installment_date = isset($_POST['next_installment_date']) ? $_POST['next_installment_date'] : '';
        $installment_id = '';

        if (!empty($installment_plan)) {
            
            $installment_query = "SELECT plan_id FROM payment_plans WHERE installment_plan = '$installment_plan'";
            $installment_result = $conn->query($installment_query);
            
            if ($installment_result->num_rows > 0) {
                $installment_row = $installment_result->fetch_assoc();
                $installment_id = $installment_row['plan_id'];
            } else {
               
            }
        }




        $user_payment_sql = "INSERT INTO user_payment (payment_id, user_id, installment_plan, initial_amount, next_amount, next_installment_date, payment_date, admin_id, installment_id)
                     VALUES ('$payment_id', '$user_id', '$installment_plan', '$initial_amount', '$next_amount', '$next_installment_date', NOW(), '$adminId', '$installment_id')";
        if ($conn->query($user_payment_sql) === TRUE) {
            
            foreach ($member_ids as $member_id) {
                $memberDetailsQuery = "INSERT INTO members_details (user_id, payment_id, start_date, expiry_date, next_installment_date, next_installment_amount)
                                       VALUES ('$member_id', '$payment_id', '$start_date', '$expiry_date', '$next_installment_date', '$next_amount')";
                $conn->query($memberDetailsQuery);
            }

            
echo "<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: 'Payment details have been inserted.',
    showConfirmButton: false,
    timer: 2000
}).then(function() {
      
    
    var redirectURL = 'generate_pdf.php?payment_id=' + $payment_id;

    // Append the selected installment plan to the redirect URL if it exists
    if ('$installment_plan' !== '') {
        redirectURL += '&installment_plan=' + '$installment_plan';
    }

    // Append the serialized member IDs to the redirect URL
    if ('$serialized_member_ids' !== '') {
        redirectURL += '&member_ids=' + '$serialized_member_ids';
    }

    // Append the selected package to the redirect URL
    if ('$package' !== '') {
        redirectURL += '&package=' + '$package';
    }

    
    if ('$FirstName' !== '') {
        redirectURL += '&FirstName=' + '$FirstName';
    }


    window.location.href = redirectURL + '&FirstName=' + document.getElementById('FirstName').value;

    
});
</script>";

        
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error inserting into user_payment table: " . $conn->error . "'
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error inserting into payments table: " . $conn->error . "'
            });
        </script>";
    }
}
?>


  <br><br>
 <div class="payment-container">
    <div class="payment-card">
        <h2>Payment Details</h2>
        <form method="POST" action="" id="paymentForm" onsubmit="return validateForm()">
            <div class="card-details">
                <label for="user_id">Select User ID:</label>
                <select name="user_id" id="user_id">
                    
                </select>
           <label for="package">Select Package:</label>
           <select name="package" id="package" onchange="calculateExpiryDate()">
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



                <label for="start_date">Package Start Date: (Y/M/D)</label>
                <input type="date" name="start_date" id="start_date" onchange="calculateExpiryDate()">

                <label for="expiry_date">Package Expiry Date: (Y/M/D)</label>
                <input type="text" name="expiry_date" id="expiry_date"  readonly>

<br>
    <div id="memberSelectBoxes" class="select-box">
    <select class="js-example-basic-single" name="member_user_id[]" id="memberUserSelect" title="Choose a user" multiple="multiple">
        
    </select>
    </div>


           <label for="package">Select Installment Plan :</label>
           <select name="installment_plan" id="installment_plan" onchange="calculateamount()">
           <option value="">Select Installment Plan</option>
          
      </select>
                <label for="amount">Package Amount:</label>
                <input type="text" name="amount" id="amount" readonly>

                <label for="initialamount">Initial Payment:</label>
                <input type="text" name="initial_amount" id="initial_amount" readonly>

                <label for="nextamount">Next Installment Amount:</label>
                <input type="text" name="next_amount" id="next_amount" readonly>

                <label for="expiry_date">Next Installment Date:</label>
                <input type="text" name="next_installment_date" id="next_installment_date"  readonly>
                
                <button type="button" class="back-button"style="background-color: gray;">Clear</button></a>
                <button type="submit" class="pay-now-button">Pay Now</button>
                <input type="hidden" id="FirstName" value="<?= htmlspecialchars($_SESSION['FirstName'], ENT_QUOTES, 'UTF-8'); ?>">

        </div>
        <button type="button" class="balance-payment-button" style="background-color: #4CAF50; color: white; padding: 15px; border: none; border-radius: 5px; cursor: pointer; margin-top: 20px;">Balance Payment</button>

    </div>
  </div>




  <script>


flatpickr("#start_date", {
    dateFormat: "Y-m-d",
    onChange: function(selectedDates, dateStr, instance) {
        
        calculateExpiryDate();
    }
});
$(document).ready(function() {
   
    $('.balance-payment-button').on('click', function() {
        // Redirect to balance_pay.php
        window.location.href = 'balance_pay.php';
    });
});
  
   function validateForm() {
        var userId = document.getElementById("user_id").value;
        var package = document.getElementById("package").value;
        var startDate = document.getElementById("start_date").value;

        if (userId === "" || package === "" || startDate === "") {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please fill in all fields!',
            });
            return false;
        }
        return true;
    }

    $(document).ready(function () {
        $('#user_id').select2({
            placeholder: 'Select User ID',
            minimumInputLength: 1, 
            ajax: {
                url: 'fetchUsernames.php', 
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        username: params.term
                    };
                },
                processResults: function (data) {
                    var results = data.map(function (user) {
                        return { id: user.id, text: user.memberno + ' - ' + user.username };
                    });

                    return {
                        results: results
                    };
                },
                cache: true
            }
        });
    });

  

    function calculateExpiryDate() {

    var selectedPackage = document.getElementById("package").value;
    var expiryDateInput = document.getElementById("expiry_date");
    var startDate = new Date(document.getElementById("start_date").value);
    var installmentPlanDropdown = document.getElementById("installment_plan");

    
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
        var response = JSON.parse(this.responseText);
        document.getElementById("amount").value = response.package_price;
        installmentPlanDropdown.innerHTML = '';
        $('#initial_amount').val('');
            $('#next_amount').val('');


        var defaultOption = document.createElement('option'); 
            defaultOption.value = '';
            defaultOption.text = 'Select Installment Plan';
            installmentPlanDropdown.appendChild(defaultOption); 


        if (response.installment_plans.length > 0) {
            response.installment_plans.forEach(function (plan) {
                var option = document.createElement('option');
                option.value = plan;
                option.text = plan;
                installmentPlanDropdown.appendChild(option);
            });
        } else {
            var option = document.createElement('option');
            option.value = '';
            option.text = 'No plans available';
            installmentPlanDropdown.appendChild(option);
        }

            var memberCount = response.member_count;
            createMemberSelectBoxes(memberCount);

            var expiryDate = new Date(startDate);
            var duration = parseInt(response.time_duration);

            switch (duration) {
                case 1:
                    expiryDate.setDate(startDate.getDate() + 1);
                    break;
                case 30:
                    expiryDate.setDate(startDate.getDate() + 30);
                    break;
                case 90:
                    expiryDate.setDate(startDate.getDate() + 90);
                    break;
                case 180:
                    expiryDate.setDate(startDate.getDate() + 180);
                    break;
                case 365:
                    expiryDate.setDate(startDate.getDate() + 365);
                    break;
                default:
                    console.log("Invalid duration");
                    return;
            }

            var formattedExpiryDate = formatDate(expiryDate);
            expiryDateInput.value = formattedExpiryDate;
        }
        sendSMS($('#memberUserSelect').val());
    };

    
    xhr.open("GET", "fetch_installment_plans.php?package=" + selectedPackage, true);
    xhr.send();
}

function calculateamount() {
    $(document).ready(function() {
        
        function fetchInstallmentDetails(selectedPlan, selectedPackage) {
            $.ajax({
                url: 'get_installment_details.php',
                method: 'GET',
                data: {
                    selectedPlan: selectedPlan,
                    selectedPackage: selectedPackage
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#initial_amount').val(response.initialAmount);
                        $('#next_amount').val(response.nextInstallmentAmount);

                        
                        $('#next_installment_date').val(calculateNextInstallmentDate(response.nextInstallmentDays));
                    } else {
                        console.error('Failed to fetch installment details');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }

        
        function calculateNextInstallmentDate(nextInstallmentDays) {
            var startDate = new Date($('#start_date').val());
            var nextInstallmentDate = new Date(startDate);
            nextInstallmentDate.setDate(startDate.getDate() + parseInt(nextInstallmentDays));

            return formatDate(nextInstallmentDate);
        }

        
        function formatDate(date) {
            const formattedDate = new Date(date);
            const year = formattedDate.getFullYear();
            let month = (formattedDate.getMonth() + 1).toString().padStart(2, '0');
            let day = formattedDate.getDate().toString().padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        
        var selectedPlan = $('#installment_plan').val();
        var selectedPackage = $('#package').val();

        
        fetchInstallmentDetails(selectedPlan, selectedPackage);

        
        $('#installment_plan').change(function() {
            var selectedPlan = $(this).val();
            var selectedPackage = $('#package').val();

            
            fetchInstallmentDetails(selectedPlan, selectedPackage);
        });
    });
}





function createMemberSelectBoxes(count) {
    var container = $("#memberSelectBoxes");
    container.empty();

    for (var i = 0; i < count; i++) {
        var selectDiv = $("<div></div>");
        selectDiv.css("margin-bottom", "10px"); 

        var selectUserDropdown = $("<select></select>");
        selectUserDropdown.attr("name", "member_user_id[]");
        selectUserDropdown.addClass("member-user-dropdown");
        selectUserDropdown.attr("title", "Choose a user");

        selectDiv.append(selectUserDropdown);
        container.append(selectDiv);

        selectUserDropdown.select2({
            placeholder: 'Select Member',
            minimumInputLength: 2,
            ajax: {
                url: 'fetchUsernames2.php',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        username: params.term 
                    };
                },
                processResults: function(data) {
                    var results = data.map(function(user) {
                        return { id: user.id, text: user.memberno + ' - ' + user.username };
                    });

                    return {
                        results: results
                    };
                },
                cache: true
            }
        });
    }
}

function formatDate(date) {
    const formattedDate = new Date(date);
    const year = formattedDate.getFullYear();
    let month = (formattedDate.getMonth() + 1).toString().padStart(2, '0');
    let day = formattedDate.getDate().toString().padStart(2, '0');

    return `${year}-${month}-${day}`;
}








$(document).ready(function() {
    $('.pay-now-button').on('click', function() {
       
    });
});



  </script>
</body>
</html>
