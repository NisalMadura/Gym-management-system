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
    <meta charset="UTF-8">
    <title style="text-align: center;">Gym Workout Schedule</title>
 
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
</style>
</head>

<?php include'header.php';?>

<body style="background-image: url('images/pic10.jpeg'); background-size: auto; background-position: center;">
<br><br><br><br>
<div class="container">
<h1 style="text-align: center; color: black; font-weight: bold;">The Colour Fitness Club Workout Schedule</h1>
    <form action="generate_schedule.php" method="post">
        <div class="form-row">
            <label for="user_id" class="user-label"style="font-size: 16px; color: black; font-weight: bold;">Select User ID:</label>
            <div id="memberSelectBoxes" class="select-box user-dropdown"></div>
        </div>
<br>
        <div class="form-row">
    <label for="start_date"style="font-size: 16px; color: black; font-weight: bold;">Start Date:</label>
    <input type="date" id="start_date" name="start_date" class="modern-input" required>
</div>

<div class="form-row">
<label for="expire_date" style="font-size: 16px; color: black; font-weight: bold;">Expire Date:</label>
    <input type="date" id="expire_date" name="expire_date" class="modern-input" required>
</div>

<div class="form-row">
    <label for="weight"style="font-size: 16px; color: black; font-weight: bold;">Weight:</label>
    <input type="number" id="weight" name="weight" class="modern-input" required>
</div>

<div class="form-row">
    <label for="height"style="font-size: 16px; color: black; font-weight: bold;">Height:</label>
    <input type="number" id="height" name="height" class="modern-input" required>
</div>



        

        <table id="exercise_table">
            <tr>
                <th>Exercise</th>
                <th>Sets</th>
            </tr>
           
        </table><br>

        <button type="button" onclick="addExercise()" class="modern-button">Add Exercise</button><br><br>

        <button type="submit" id="generateScheduleButton" class="modern-button">Generate Schedule</button>
    </form>
</div>

    <script>
let rowCount = 0;

function addExerciseToTable(name, sets) {
    const table = document.getElementById('exercise_table');
    rowCount++;

    const row = table.insertRow();
    const cell1 = row.insertCell(0);
    const cell2 = row.insertCell(1);
    const cell3 = row.insertCell(2);

    // Create hidden inputs with proper names for exercise names and sets
    const exerciseNameInput = `<input type="hidden" name="exercise_name[]" value="${name}">${name}`;
    const exerciseSetsInput = `<input type="hidden" name="exercise_sets[]" value="${sets}">${sets}`;

    
    const deleteButton = `<button type="button" onclick="deleteExercise(this)" class="delete-button">Delete</button>`;

    cell1.innerHTML = exerciseNameInput;
    cell2.innerHTML = exerciseSetsInput;
    cell3.innerHTML = deleteButton;
}

function deleteExercise(button) {
    const row = button.parentNode.parentNode;
    row.parentNode.removeChild(row);
}

function addExercise() {
    Swal.fire({
        title: 'Add Exercise',
        html:
            '<label for="exerciseName">Exercise Name:</label>' +
            '<input type="text" id="exerciseName" class="swal2-input">' +
            '<label for="exerciseSets">Sets:</label>' +
            '<input type="text" id="exerciseSets" class="swal2-input">',
        showCancelButton: true,
        confirmButtonText: 'Add',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const exerciseName = Swal.getPopup().querySelector('#exerciseName').value;
            const exerciseSets = Swal.getPopup().querySelector('#exerciseSets').value;
            if (!exerciseName || !exerciseSets) {
                Swal.showValidationMessage(`Please enter both Exercise Name and Sets`);
            }
            return { exerciseName: exerciseName, exerciseSets: exerciseSets };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const exerciseName = result.value.exerciseName;
            const exerciseSets = result.value.exerciseSets;
            addExerciseToTable(exerciseName, exerciseSets);

            // Get the selected user ID and Name
            const selectedUserId = $('#memberSelectBoxes').find('.js-example-basic-single').val();
            const selectedUserName = $('#memberSelectBoxes').find('.js-example-basic-single').text();

            // Store the selected user ID and Name in hidden input fields
            $('<input>').attr({
                type: 'hidden',
                name: 'user_id',
                value: selectedUserId
            }).appendTo('form');

            $('<input>').attr({
                type: 'hidden',
                name: 'username',
                value: selectedUserName
            }).appendTo('form');
        }
    });
}

$(document).ready(function() {
    createDropdown();
});


function createDropdown() {
    var selectUserDropdown = $("<select></select>");
    selectUserDropdown.attr("name", "member_user_id[]");
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


  $("#generateScheduleButton").click(function() {
    
});
    </script>
</body>
</html>
