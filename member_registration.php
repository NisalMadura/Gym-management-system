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
    <title style="text-align: center;">Gym Workout Schedule</title>
 
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

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

.delete-button {
    display: inline-block;
    padding: 6px 10px; 
    font-size: 14px; 
    border: none;
    border-radius: 3px;
    background-color: #e74c3c; 
    color: white;
    text-align: center;
    text-decoration: none;
    transition: background-color 0.3s;
    cursor: pointer;
}

.delete-button:hover {
    background-color: #c0392b; 
}

#registration_fee {
        width: 100%;
    }

    .select2-container {
        width: 100% !important;
    }

    .select2-selection--single {
        height: 38px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #fff; 
    }

    .select2-selection__arrow {
        height: 36px;
    }

    .select2-results__option {
        padding: 8px;
    }
    #amount {
    width: 100%;
    padding: 10px;
    border: 2px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
    margin-bottom: 10px;
    box-sizing: border-box;
}
   
#exercise_table th:first-child,
#exercise_table td:first-child {
    display: none;
}

</style>
</head>

<?php include'header.php';?>

<body style="background-image: url('images/pic26.jpg'); background-size: auto; background-position: center;">
<br><br><br><br>
<div class="container">
    <h1 style="text-align: center; color: black; font-weight: bold;">The Colour Fitness Club Member Registration</h1>
    <form id="paymentForm" action="receipt_reg.php" method="post">

    <div class="form-row">
            <label for="payment_user_id" class="user-label" style="font-size: 16px; color: black; font-weight: bold;">Payment User:</label>
            <div id="paymentSelectBoxes" class="select-box user-dropdown">

            </div>
            
        </div>
        <br>

        <div class="form-row">
            <label for="user_id" class="user-label" style="font-size: 16px; color: black; font-weight: bold;">Select Member:</label>
            <div id="memberSelectBoxes" class="select-box user-dropdown">

            </div>
            
        </div>
        
            <button type="button" onclick="addExercise()" class="modern-button add-member-button" style="display: inline-block; margin-left: 170px; background-color: #87CEEB;">Add Member</button><br>
        <br>

        <table id="exercise_table">
            <tr>
                <th>User ID</th>
                <th>Member No</th>
                <th>Username</th>
                <th>Action</th>
            </tr>
        </table>
        <br>

        <label for="registration_fee">Registration Fee:</label><br><br>
        <select id="registration_fee" name="registration_fee" required>
        <option value="">Select Registration Fee Type</option>
        <?php
          require 'connectDB.php'; 
          $sql = "SELECT registrationFeeType FROM registrationfee_type";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
           while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['registrationFeeType'] . '">' . $row['registrationFeeType'] . '</option>';
        }
        } else {
          echo '<option value="">No option available</option>';
        }
       ?>
        </select>
<br><br><br>
<label for="amount">Amount:</label><br><br>
                <input type="text" name="amount" id="amount" readonly>
                <br><br><br>
                
          <input type="hidden" id="registration_type_id" name="registration_type_id" value="">
          <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $_SESSION['AdminId']; ?>">
          <input type="hidden" name="first_name" value="<?php echo $_SESSION['FirstName']; ?>">

        <div class="button-container">
        <button type="button" onclick="payNow(); submitPaymentForm();" class="modern-button add-member-button" style="display: inline-block; margin-left: 220px;">Pay Now</button>

        </div>
    </form>
</div>

<script>
function createDropdown() {
    var selectUserDropdown = $("<select></select>");
    selectUserDropdown.attr("name", "member_user_id[]");
    selectUserDropdown.addClass("js-example-basic-single form-control");
    selectUserDropdown.attr("title", "Choose a user");
    selectUserDropdown.css("width", "40%");

    $("#memberSelectBoxes").append(selectUserDropdown);

    selectUserDropdown.select2({
        placeholder: 'Select Member',
        minimumInputLength: 2,
        ajax: {
            url: 'fetchUsernames3.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    username: params.term
                };
            },
            processResults: function (data) {
                var results = data.map(function (user) {
                    return { id: user.id, text: user.text };
                });

                return {
                    results: results
                };
            },
            cache: true
        }
    });
}


$(document).ready(function () {
    createDropdown();
});





function addExerciseToTable(userId, userName, memberNo) {
    const table = document.getElementById('exercise_table');

    const existingUser = Array.from(table.rows).find(row => row.cells[0].innerHTML === userId);

    if (!existingUser) {
        const row = table.insertRow();
        const cell1 = row.insertCell(0);
        const cell2 = row.insertCell(1);
        const cell3 = row.insertCell(2);
        const cell4 = row.insertCell(3);

        cell1.innerHTML = userId;
        cell2.innerHTML = memberNo;
        cell3.innerHTML = extractUsername(userName);
        cell4.innerHTML = '<button type="button" onclick="deleteExercise(this)" class="delete-button">Delete</button>';
    } else {
        alert('User already added to the table.');
    }
}




function addExercise() {
    const selectedUserId = $('#memberSelectBoxes select').val();
    const selectedUserName = $('#memberSelectBoxes select option:selected').text();
    const selectedMemberNo = selectedUserName.split(' - ')[0]; // Extract member number

    const existingUserIds = Array.from($('#exercise_table td:first-child'), cell => cell.textContent);

    if (selectedUserId && selectedUserName && selectedMemberNo) {
        if (existingUserIds.includes(selectedUserId)) {
            alert('User is already added to the table.');
        } else {
            addExerciseToTable(selectedUserId, selectedUserName, selectedMemberNo);
        }
    } else {
        alert('Please select a user from the dropdown before adding.');
    }
}



function extractUsername(fullUsername) {
    const separatorIndex = fullUsername.indexOf('-');
    return separatorIndex !== -1 ? fullUsername.substring(separatorIndex + 1).trim() : fullUsername.trim();
}

    
    function deleteExercise(button) {
        const row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

   


    $(document).ready(function () {
        
        $('#registration_fee').select2({
            theme: 'classic', 
            placeholder: 'Select Registration Fee',
            allowClear: true
        });
    });



    function createPaymentDropdown() {
        var selectUserDropdown = $("<select></select>");
        selectUserDropdown.attr("name", "member_user_id[]");
        selectUserDropdown.addClass("js-example-basic-single form-control");
        selectUserDropdown.attr("title", "Choose a user");
        selectUserDropdown.css("width", "40%");

        $("#paymentSelectBoxes").append(selectUserDropdown);

        selectUserDropdown.select2({
            placeholder: 'Select User ID with Name',
            minimumInputLength: 2,
            ajax: {
                url: 'fetchUsernames3.php',
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
    }

    $(document).ready(function () {
        createPaymentDropdown();
    });

    

    function updateAmount() {
    var registrationFeeType = $('#registration_fee').val();

    if (registrationFeeType) {
        $.ajax({
            type: 'GET',
            url: 'get_fee_amount.php',
            data: {
                registrationFeeType: registrationFeeType
            },
            success: function (response) {
                console.log('Server response:', response);

                try {
                    var responseData = JSON.parse(response);

                    if (responseData.success) {
                        // Store registration type ID in the global variable
                        registrationTypeId = responseData.id;

                        var feeAmount = parseFloat(responseData.feeAmount);

                        if (!isNaN(feeAmount)) {
                            var numberOfMembers = $('#exercise_table tr').length - 1;
                            var amount = numberOfMembers * feeAmount;

                            $('#amount').val(amount.toFixed(2));

                            // Set the value of the hidden input field
                            $('#registration_type_id').val(registrationTypeId);
                        } else {
                            console.error('Invalid fee amount received from the server.');
                        }
                    } else {
                        console.error('Server response indicates failure:', responseData.message);
                    }
                } catch (error) {
                    console.error('Error parsing server response:', error);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error making AJAX request:', error);
            }
        });
    } else {
        console.error('No registration fee type selected.');
    }
}



    
    $(document).ready(function () {
        updateAmount();
    });

    
    $('#registration_fee').change(function () {
        updateAmount();
    });

    function payNow() {
    var paymentUserId = $('#paymentSelectBoxes select').val();
    var memberUserIds = $('#exercise_table td:first-child').map(function () {
        return $(this).text();
    }).get();
    var feeType = $('#registration_fee').val();
    var amount = $('#amount').val();
    var registrationTypeId = $('#registration_type_id').val();
    var adminId= $('#admin_id').val();
    var firstName = $('#first_name').val();
    

    $.ajax({
        type: 'POST',
        url: 'process_payment.php',
        data: {
            payment_user_id: paymentUserId,
            member_user_id: memberUserIds,
            fee_type: feeType,
            registrationTypeId: registrationTypeId,
            amount: amount,
            firstName: firstName,
            adminId: adminId
        },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                
                Swal.fire({
                    icon: 'success',
                    title: 'Payment Successful',
                    text: response.message,
                }).then(function() {
                    
                    sendSMS();
                    location.reload();
                });
            } else {
              
                Swal.fire({
                    icon: 'error',
                    title: 'Payment Error',
                    text: response.message,
                });
            }
        },
        error: function (xhr, status, error) {
            
            Swal.fire({
                icon: 'error',
                title: 'Oops',
                text: 'Please fill all fields and try again.'+console.error(),
            });
        }
    });
}
function submitPaymentForm() {
    
    var paymentUserId = $('#paymentSelectBoxes select').val();
    var memberUserIds = $('#exercise_table td:first-child').map(function () {
        return $(this).text();
    }).get();
    var feeType = $('#registration_fee').val();
    var amount = $('#amount').val();

    
    $('#paymentForm').append('<input type="hidden" name="payment_user_id" value="' + paymentUserId + '">');

    
    for (var i = 0; i < memberUserIds.length; i++) {
        $('#paymentForm').append('<input type="hidden" name="member_user_ids[]" value="' + memberUserIds[i] + '">');
    }

    $('#paymentForm').append('<input type="hidden" name="fee_type" value="' + feeType + '">');
    $('#paymentForm').append('<input type="hidden" name="amount" value="' + amount + '">');

    
    $('#paymentForm').submit();
}

function sendSMS() {
    var memberUserIds = $('#exercise_table td:first-child').map(function () {
        return $(this).text();
    }).get();

    $.ajax({
        type: "POST",
        url: "send_smss.php",
        data: {
            
            member_user_id: JSON.stringify(memberUserIds),
        },
        success: function (response) {
            alert(response);
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}




</script>
</body>
</html>
