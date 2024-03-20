<?php
error_reporting(0);
ob_start();
session_start();
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
<body style="background-image: url('images/pic26.jpg'); background-size: flex; background-position: center;">



  <br><br>
 <div class="payment-container">
    <div class="payment-card">
        <h2>Member Registration</h2>
        <form method="POST" action="" id="paymentForm" onsubmit="return validateForm()">
            <div class="card-details">
                <label for="user_id">Select Payment User ID:</label>
                <select name="user_id" id="user_id">
                    
                </select>
                <label for="member_count">Number of Members:</label>
               <!-- Replace the standard number input with a modern text box using inputmask -->
<input type="text" name="member_count" id="member_count" data-inputmask="'mask': '9', 'repeat': 10, 'greedy' : false" placeholder="Enter Number of Members" onchange="generateMemberDropdowns()">




<br>
<div id="memberSelectBoxes" class="select-box">
    <!-- Dropdowns will be dynamically added here based on member count -->
</div>


           <label for="registration_fee">Select Registration Fee Type :</label>
           <select id="registration_fee" name="registration_fee" required>
           <option value="">Select Registration Fee Type</option>
                <option value="no_fee">No Registration Fee</option>
                <option value="fee_only">Registration Fee Only</option>
                <option value="fee_additional">Registration Fee + Additional Charges Apply</option>
            </select>
                <label for="amount">Amount:</label>
                <input type="text" name="amount" id="amount" readonly>

          
                
                <button type="button" class="back-button"style="background-color: gray;">Clear</button></a>
                <button type="submit" class="pay-now-button">Pay Now</button>
                
        </div>

    </div>
  </div>




  <script>

function savePaymentDetails() {
    var userId = $("#user_id").val();
    var memberCount = $("#member_count").val();
    var registrationFeeType = $("#registration_fee").val();
    var amount = $("#amount").val();

    if (userId === "" || memberCount === "" || registrationFeeType === "" || amount === "") {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Please fill in all fields!',
        });
        return false; 
    }


    return true;
}

function handlePaymentSubmission() {
    if (savePaymentDetails()) {
        
        $.ajax({
            url: 'save_payment_details.php',
            method: 'POST',
            data: $('#paymentForm').serialize(), 
            dataType: 'json', 
            success: function(response) {
                if (response.success) {
                   
                    Swal.fire({
                        icon: 'success',
                        title: 'Payment Successful',
                        text: 'Your payment has been processed successfully!',
                        
                    });

                   
                    setTimeout(function() {
                        location.reload();
                    }, 3000); 
                } else {
                
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message || 'Error processing payment.',
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);

                
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An error occurred while processing your payment.',
                });
            }
        });
    }
}

$(document).ready(function() {
    
    function calculateAmount() {
        var registrationFeeType = $("#registration_fee").val();
        var memberCount = parseInt($("#member_count").val());

        
        $.ajax({
            url: 'get_fee_amount.php',
            method: 'GET',
            data: { registrationFeeType: registrationFeeType },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var feeAmount = parseFloat(response.feeAmount);

                    
                    var amount = feeAmount * memberCount;

                    
                    $("#amount").val(amount.toFixed(2));
                } else {
                    console.error(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }

    
    $("#registration_fee").on("change", calculateAmount);

    

    $("#member_count").on("input", calculateAmount);

   
});



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
                        return { id: user.id, text: user.id + ' - ' + user.username };
                    });

                    return {
                        results: results
                    };
                },
                cache: true
            }
        });
    });


function generateMemberDropdowns() {
    var memberCount = parseInt(document.getElementById("member_count").value);
    

    $("#memberSelectBoxes").empty();

    
    for (var i = 0; i < memberCount; i++) {
        var selectDiv = $("<div></div>");
        selectDiv.css("margin-bottom", "10px");

        var selectUserDropdown = $("<select></select>");
        selectUserDropdown.attr("name", "member_user_id[]");
        selectUserDropdown.addClass("member-user-dropdown");
        selectUserDropdown.attr("title", "Choose a user");

        selectDiv.append(selectUserDropdown);
        $("#memberSelectBoxes").append(selectDiv);

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
                        return { id: user.id, text: user.id + ' - ' + user.username };
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


$(document).ready(function() {
    $('.back-button').on('click', function() {
        $('#paymentForm')[0].reset(); 
    });
    $('#paymentForm').on('submit', function(e) {
        e.preventDefault(); 
        handlePaymentSubmission();
    });
});


  </script>
</body>
</html>
