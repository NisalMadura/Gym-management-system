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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gym Workout Schedule</title>
    
   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background:white;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px;
        }

        table, th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-row {
            margin-bottom: 10px;
        }

        .form-row label {
            display: inline-block;
            width: 150px; 
            margin-right: 10px;
            text-align: right;
        }

        .form-row input {
            width: 200px; 
        }

        .user-label {
            display: inline-block;
            width: 350px;
            margin-right: 10px;
            text-align: right;
        }

        .user-dropdown {
            display: inline-block;
            width: 370px;
        }

        .modern-input {
            padding: 8px;
            border: 2px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            width: 100px; 
            margin-bottom: 10px;
        }

        .modern-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50; 
            color: white;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .modern-button:hover {
            background-color: #45a049; 
        }
    </style>
</head>

<?php include 'header.php';?>

<body style="background-image: url('images/pic22.jpg'); background-size: 160%; background-position: center;">
    <br><br><br><br>
    <div class="container">
        <h1 style="text-align: center; color: black; font-weight: bold;">Package Renew Portal</h1>
        <form action="" method="GET">
        <div class="form-row">
                <label for="username" class="user-label" style="font-size: 16px; color: black; font-weight: bold;">Select Username:</label>
                <div id="memberSelectBoxes" class="select-box user-dropdown"></div>
            </div>
            <br>
            <div class="form-row">
                <label for="paymentid" class="user-label" style="font-size: 16px; color: black; font-weight: bold;">Select Payment ID:</label>
                <input type="text" id="payment_id" name="payment_id" class="modern-input" readonly>
            
            </div>
            <br>
          
            <div class="form-row">
                <label for="start_date" style="font-size: 16px; color: black; font-weight: bold;">Start Date:</label>
                <input type="text" id="start_date" name="start_date" class="modern-input" readonly>
            </div>

            <div class="form-row">
                <label for="expire_date" style="font-size: 16px; color: black; font-weight: bold;">Expire Date:</label>
                <input type="text" id="expire_date" name="expire_date" class="modern-input" readonly>
            </div>

            <div class="form-row">
                <label for="next_installment_date" style="font-size: 16px; color: black; font-weight: bold;">Next Installment Date:</label>
                <input type="text" id="next_installment_date" name="next_installment_date" class="modern-input" readonly>
            </div>

            <div class="form-row">
                <label for="next_installment_amount" style="font-size: 16px; color: black; font-weight: bold;">Next Installment Amount Rs:</label>
                <input type="text" id="next_installment_amount" name="next_installment_amount" class="modern-input" readonly>
            </div>

            <div style="text-align: right;">
                <button type="button" id="payNowButton" class="modern-button">Pay Now</button>
         
                <input type="hidden" id="admin_id" name="admin_id" value="<?php echo $_SESSION['AdminId']; ?>">

            </div>

            

           
        </form>
    </div>

    
    <script>
        $(document).ready(function () {
            createUsernameDropdown();

            function createUsernameDropdown() {
                var selectUserDropdown = $("<select></select>");
                selectUserDropdown.attr("name", "username"); 
                selectUserDropdown.addClass("js-example-basic-single form-control");
                selectUserDropdown.attr("title", "Choose a user");
                selectUserDropdown.css("width", "40%");

                $("#memberSelectBoxes").append(selectUserDropdown);

                selectUserDropdown.select2({
                    placeholder: 'Select User ID with Name',
                    minimumInputLength: 2,
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

                selectUserDropdown.on('change', function () {
                    var selectedUserId = $(this).val();
                    if (selectedUserId) {
                        $.ajax({
                            url: 'fetchDetails.php',
                            type: 'GET',
                            data: { user_id: selectedUserId },
                            dataType: 'json',
                            success: function (data) {
                                if (data.hasOwnProperty('error')) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.error
                                    }).then(function () {
                                        location.reload();
                                    });
                                } else {
                                    // Update the input fields with fetched details
                                    $('#payment_id').val(data.payment_id);
                                    $('#start_date').val(data.start_date);
                                    $('#expire_date').val(data.expiry_date);
                                    $('#next_installment_date').val(data.next_installment_date);
                                    $('#next_installment_amount').val(data.next_installment_amount);
                                    displayOtherUsersTable(data.otherUsers);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Error fetching details:', error);
                            }
                        });
                    }
                });
            }

$('#payNowButton').on('click', function () {
        var paymentId = $('#payment_id').val();
        var userId = $('select[name="username"]').val();
        var expireDate = $('#expire_date').val();
        var nextInstallmentDate = $('#next_installment_date').val();
        var nextInstallmentAmount = $('#next_installment_amount').val();
        var adminId = $('#admin_id').val();

        Swal.fire({
            title: 'Confirm Payment',
            text: 'Are you sure you want to proceed with the payment?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Pay Now',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (result.isConfirmed) {
                
                $.ajax({
                    url: 'updatePayment.php',
                    type: 'POST',
                    data: {
                        payment_id: paymentId,
                        user_id: userId,
                        expire_date: expireDate,
                        next_installment_date: nextInstallmentDate,
                        next_installment_amount: nextInstallmentAmount,
                        adminId: adminId
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            // Generate PDF after successful payment
                            generatePDF(paymentId);

                            // Display success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Payment Successful',
                                text: 'The payment has been successfully processed.'
                            }).then(function () {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Payment Error',
                                text: response.error
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error updating payment:', error);
                    }
                });
            }
        });
    });

    function generatePDF(paymentId) {
    var firstName = '<?php echo $_SESSION["FirstName"]; ?>';
    window.location.href = 'download_pdf.php?payment_id=' + paymentId + '&FirstName=' + firstName;
}

});
</script>
</body>
</html>
