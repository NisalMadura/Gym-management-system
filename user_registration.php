<?php
session_start();
require_once 'connectDB.php'; // Establish database connection

if (isset($_POST['submit'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $idnumber = $_POST['idnumber'];
    $email = $_POST['email'];
    $contactNo = $_POST['contactNo'];
    $username = $_POST['UserName1'];
    $password = $_POST['password_1'];
    $Role = $_POST['Role'];

    

    // Insert data into the database
    $sql = "INSERT INTO user_admin (FirstName, LastName, IDNumber, ContactNo, EmailAddress, Username, Password,Role) VALUES (?, ?, ?, ?, ?, ?, ?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $firstName, $lastName, $idnumber, $contactNo, $email, $username, $password,$Role);
    $stmt->execute();

    // Check if the data was inserted successfully
    if ($stmt->affected_rows > 0) {
        // Registration successful, set success message and redirect
        $_SESSION['message_suc'] = "Registration Successful!";
        $_SESSION['alert_type'] = "success";
        header("Location: create_New_User.php");
        exit();
    } else {
        // Handle insertion failure
        $_SESSION['message_suc'] = "Registration Failed!";
        $_SESSION['alert_type'] = "error";
        header("Location: create_New_User.php");
        exit();
    }
}
?>
