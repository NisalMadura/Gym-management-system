<?php
require 'connectDB.php';

if (isset($_GET['username'])) {
    $searchQuery = $_GET['username'];

    $stmt = $conn->prepare("SELECT u.id, u.username,u.memberno 
                           FROM users u 
                           LEFT JOIN members_details md ON u.id = md.user_id 
                           WHERE md.user_id IS NULL AND u.username LIKE ?");
    
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
                'text' => $row['memberno'] != '' ? $row['id'] . ' - ' . $row['memberno'] . ' - ' . $row['username'] : 'Select Member'
            );
        }

        if (count($usernames) > 0) {
            array_unshift($usernames, array('id' => '', 'username' => 'Select Member'));
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
