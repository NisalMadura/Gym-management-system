<?php
require 'connectDB.php';

if (isset($_GET['username'])) {
    $searchQuery = $_GET['username'];

    $stmt = $conn->prepare("SELECT id, username, memberno FROM users
    WHERE username LIKE ? AND id NOT IN (SELECT member_user_id FROM registration_details)");

    if ($stmt) {
        $searchQuery = '%' . $searchQuery . '%';
        $stmt->bind_param('s', $searchQuery);
        $stmt->execute();

        $result = $stmt->get_result();

        $usernames = array();

        while ($row = $result->fetch_assoc()) {
            $usernames[] = array(
                'id' => $row['id'],
                'username' => $row['username'],
                'memberno' => $row['memberno'],
                'text' => $row['memberno'] != '' ? $row['memberno'] . ' - ' . $row['username'] : 'Select Member'
            );
        }

        if (count($usernames) > 0) {
            echo json_encode($usernames);
        } else {
            echo json_encode(['error' => 'No matching users found']);
        }
    } else {
        echo json_encode(['error' => 'Failed to prepare statement']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([]);
}
?>
