<?php
require 'connectDB.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM packages WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // Deletion succeeded
            echo "success";
        } else {
            // Deletion failed
            echo "failure";
        }
    } else {
        // Statement preparation error
        echo "error";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
