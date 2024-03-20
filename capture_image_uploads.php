<?php
require 'connectDB.php';

$folderPath = './capture_images/';

if (isset($_FILES['image']) && isset($_POST['user_id'])) {
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_name = $_FILES['image']['name'];
    $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    $user_id = $_POST['user_id']; 
    
    $file_path = $folderPath . $user_id . '.' . $file_extension;

    $allowed_extensions = array('jpg', 'jpeg', 'png');
    if (in_array($file_extension, $allowed_extensions)) {
        
        $sql_check = "SELECT * FROM users_photo WHERE user_id = '$user_id'";
        $result = $conn->query($sql_check);

        if ($result->num_rows > 0) {
            
            $sql = "UPDATE users_photo SET photo = '$file_path' WHERE user_id = '$user_id'";
        } else {
            
            $sql = "INSERT INTO users_photo (user_id, photo) VALUES ('$user_id', '$file_path')";
        }

        if (move_uploaded_file($image_tmp_name, $file_path) && $conn->query($sql) === TRUE) {
            echo json_encode(["Image uploaded successfully."]);
        } else {
            echo json_encode(["Failed to upload image or update database."]);
        }
    } else {
        echo json_encode(["Invalid file format. Only JPG, JPEG, and PNG are allowed."]);
    }
} else {
    echo json_encode(["No image data received or user ID not provided."]);
}
?>
