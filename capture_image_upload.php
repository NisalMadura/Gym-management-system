<?php
require 'connectDB.php';

$folderPath = './capture_images/';

if (isset($_POST['image']) && isset($_POST['user_id'])) {
    $image_parts = explode(";base64,", $_POST['image']);
    $user_id = $_POST['user_id'];

    if (count($image_parts) >= 2) {
        $image_type_aux = explode("image/", $image_parts[0]);
        
        if (count($image_type_aux) >= 2) {
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . $user_id . '.' . $image_type; 
            
            
            $sql_check = "SELECT * FROM users_photo WHERE user_id = '$user_id'";
            $result = $conn->query($sql_check);

            if ($result->num_rows > 0) {
                
                $sql = "UPDATE users_photo SET photo = '$file' WHERE user_id = '$user_id'";
            } else {
                
                $sql = "INSERT INTO users_photo (user_id, photo) VALUES ('$user_id', '$file')";
            }

            if (file_put_contents($file, $image_base64) !== false && $conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Image uploaded successfully."]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to upload image or update database."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid image format."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid image data."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No image data received or user ID not provided."]);
}
?>
