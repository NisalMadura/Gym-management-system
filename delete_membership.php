<?php
require 'connectDB.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $plan_id = $_POST['id'];

    $sql = "DELETE FROM payment_plans WHERE plan_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $plan_id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // Deletion succeeded
            echo json_encode(['status' => 'success']);
        } else {
            // Deletion failed
            echo json_encode(['status' => 'failure']);
        }
    } else {
        // Statement preparation error
        echo json_encode(['status' => 'error']);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
