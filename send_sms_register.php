<?php

function sendSMSToMember($MemberNo, $message,$Phone) {
    $smsConfig = getSmsConfig();

    if ($smsConfig) {
        $api_endpoint_sms = 'http://send.ozonedesk.com/api/v2/send.php';

        $phoneNumbers = getUserPhoneNumbers([$MemberNo]);

        if (!empty($phoneNumbers)) {
            $phoneNumber = $phoneNumbers[0];

            $sms_params = [
                'user_id'   => $smsConfig['user_id'],
                'api_key'   => $smsConfig['api_key'],
                'sender_id' => $smsConfig['sender_id'],
                'to'        => $Phone,
                'message'   => $message,
            ];

            $api_url_sms = $api_endpoint_sms . '?' . http_build_query($sms_params);
            $response_sms = file_get_contents($api_url_sms);

            
        } else {
            echo 'No phone number found for user with ID ' . $MemberNo . '<br>';
        }
    } else {
        echo 'Failed to retrieve SMS configuration from the database.';
        error_log('Failed to retrieve SMS configuration from the database.');
    }
}


function getSmsConfig() {
    require 'connectDB.php';

    $sql = "SELECT user_id, api_key, sender_id FROM sms_config";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return [
            'user_id'   => $row['user_id'],
            'api_key'   => $row['api_key'],
            'sender_id' => $row['sender_id'],
        ];
    } else {
        error_log('Failed to retrieve SMS configuration from the database.');
        return false;
    }
}

function getUserPhoneNumbers($MemberNo) {
    require 'connectDB.php';

    $MemberNoString = implode(',', $MemberNo); 

    $sql = "SELECT phonenumber FROM users WHERE memberno IN ($MemberNoString)";
    $result = $conn->query($sql);

    $phoneNumbers = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $phoneNumbers[] = $row['phonenumber'];
        }
    }

    return $phoneNumbers;
}



?>
