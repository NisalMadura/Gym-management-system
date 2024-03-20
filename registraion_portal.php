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
<html lang="en">
<head>
    
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Fitness Center Registration</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        display: auto;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
    .background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 400px;
        margin: auto;
        margin-top: 50px;
    }
    label {
        display: block;
        margin-bottom: 8px;
    }

    input, select {
        width: 100%;
        padding: 8px;
        margin-bottom: 16px;
        box-sizing: border-box;
    }

    .pay-btn {
        width: 100%;
        padding: 10px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .pay-btn:hover {
        background-color: #45a049;
    }

</style>
</head>

<?php include 'header.php';?>

<body style="background-image: url('images/pic2.png'); background-size: cover; background-position: center;">
    <div class="container">
        <form action="process.php" method="post">
            <label for="no_members">Enter No Members:</label>
            <input type="number" id="no_members" name="no_members" min="1" required>
            
            <div id="dropdowns"></div>

            <label for="registration_fee">Registration Fee:</label>
            <select id="registration_fee" name="registration_fee" required>
                <option value="no_fee">No Registration Fee</option>
                <option value="fee_only">Registration Fee Only</option>
                <option value="fee_additional">Registration Fee + Additional Charges Apply</option>
            </select>

            <button type="submit" class="pay-btn">Pay Now</button>
        </form>
        <script>
            var usersData = <?php echo json_encode($users); ?>;

            $(document).ready(function() {
                // Initialize Select2 on the dropdowns
                $('.select2').select2();

                // Add event listener to the "Pay Now" button
                $('.pay-btn').click(function(e) {
                    e.preventDefault();
                    displaySelectedUsers();
                });

                document.getElementById('no_members').addEventListener('input', function() {
                    createDropdowns(this.value);
                });

                function createDropdowns(num) {
                    var dropdownContainer = document.getElementById('dropdowns');
                    dropdownContainer.innerHTML = '';

                    for (var i = 0; i < num; i++) {
                        var dropdown = document.createElement('select');
                        dropdown.name = 'member_dropdown[]';
                        dropdown.required = true;

                        // Fetch options for the dropdown dynamically
                        fetchOptions(dropdown);

                        dropdownContainer.appendChild(dropdown);
                    }

                    // Initialize Select2 on the newly created dropdowns
                    $('.select2').select2();
                }

                function fetchOptions(dropdown) {
    // Make an AJAX request to fetch options from your backend
    $.ajax({
        url: 'fetchUsernames.php',  // Adjust the URL to your backend script
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            try {
                if (Array.isArray(data) && data.length > 0) {
                    // If data is an array with at least one item, proceed to populate options
                    data.forEach(function(option) {
                        var optionElement = document.createElement('option');
                        optionElement.value = option.id;
                        optionElement.text = option.username; // Adjust to your actual field name
                        dropdown.appendChild(optionElement);
                    });
                } else {
                    // If no matching users are found or data is empty, display a message
                    console.log('No matching users found or data is empty.');
                }
            } catch (error) {
                console.error('Error processing data:', error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error fetching options:', textStatus, errorThrown);
        }
    });
}


                function displaySelectedUsers() {
    var selectedUsers = [];
    $('select[name^="member_dropdown"]').each(function() {
        var selectedUserId = $(this).val();
        if (selectedUserId) {
            selectedUsers.push(selectedUserId);
        }
    });

    if (selectedUsers.length > 0) {
        // Filter users based on selected user IDs
        var selectedUsersData = usersData.filter(function(user) {
            return selectedUsers.includes(user.id.toString());
        });

        // Display user information (replace this with your logic)
        if (selectedUsersData.length > 0) {
            var userInfo = "Selected Users:\n";
            selectedUsersData.forEach(function(user) {
                userInfo += "ID: " + user.id + ", Name: " + user.name + "\n";
            });
            alert(userInfo);
        } else {
            alert('No user information found for the selected IDs.');
        }
    } else {
        alert('Please select at least one user.');
    }
}

            });
        </script>
    </div>
    
</body>
</html>
