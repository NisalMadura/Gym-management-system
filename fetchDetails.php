<?php
require 'connectDB.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Query to get the latest details for the selected user
    $query = "SELECT * FROM members_details WHERE user_id = $user_id ORDER BY payment_id DESC LIMIT 1";

    // Execute the query and fetch data
    $result = mysqli_query($conn, $query);

    if (!$result) {
        // Handle database query error
        die('Error executing query: ' . mysqli_error($conn));
    }

    // Check if any rows were returned
    if (mysqli_num_rows($result) > 0) {
        // Fetch data as an associative array
        $data = mysqli_fetch_assoc($result);

        // Send the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        // No data found for the user_id
        $response = ['error' => 'No data found for the specified user_id'];
        echo json_encode($response);
    }
} else {
    // Handle error if user_id is not set
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'user_id parameter is missing']);
}
?>
