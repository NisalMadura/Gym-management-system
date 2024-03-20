<?php
function sendSMSToMembers($memberIds, $message) {
    $smsConfig = getSmsConfig();

    if ($smsConfig) {
        $api_endpoint_sms = 'http://send.ozonedesk.com/api/v2/send.php';

        foreach ($memberIds as $memberId) {
            $phoneNumbers = getUserPhoneNumbers([$memberId]);

            if (!empty($phoneNumbers)) {
                $phoneNumber = $phoneNumbers[0];

                $sms_params = [
                    'user_id'   => $smsConfig['user_id'],
                    'api_key'   => $smsConfig['api_key'],
                    'sender_id' => $smsConfig['sender_id'],
                    'to'        => $phoneNumber,
                    'message'   => $message,
                ];

                $api_url_sms = $api_endpoint_sms . '?' . http_build_query($sms_params);
                $response_sms = file_get_contents($api_url_sms);

                if ($response_sms !== false) {
                    $response_data_sms = json_decode($response_sms, true);

                    if (isset($response_data_sms['status']) && $response_data_sms['status'] == 'success') {
                        echo 'SMS sent successfully to ' . $phoneNumber . '<br>';
                    } else {
                        echo 'Failed to send SMS to ' . $phoneNumber . '. Error: ' . $response_data_sms['msg'][0] . '<br>';
                    }
                } else {
                    echo 'Failed to connect to the Ozonedesk API for SMS.<br>';
                }
            } else {
                echo 'No phone number found for user with ID ' . $memberId . '<br>';
            }
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

function getUserPhoneNumbers($userIds) {
    require 'connectDB.php';

    $userIdsString = implode(',', $userIds);
    $sql = "SELECT phonenumber FROM users WHERE id IN ($userIdsString)";
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
