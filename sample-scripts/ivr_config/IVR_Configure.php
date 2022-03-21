<?php
/* 
 * Script to change the configuration of an existing IVR.
 * 
 * Documentation: https://docs.sonetel.com/docs/sonetel-documentation/YXBpOjE2ODMxMTg3-voice-apps
 * 
 */

// API Access token
$access_token = "ENTER_ACCESS_TOKEN";

// Sonetel account ID
$acc_id =  "ENTER_ACCOUNT_ID";

// Voice app ID
$app_id = "ENTER_APP_ID";

// SAMPLE JSON BODY
$json = '{
    "app_type": "ivr",
    "play_menu": "yes",
    "name": "ENTER_IVR_NAME",
    "voice": "en",
    "play_welcome": "yes",
    "get_extension": "yes",
    "menu": {
        "digit_1": {
                "action": "call",
                "to": "phnum",
                "id": "ENTER_PHONE_NUMBER"
            },
        "digit_2": {
            "action": "call",
            "to": "user",
            "id": "ENTER_USER_ID"
          },
          "digit_3": {
            "action": "connect",
            "to": "app",
            "id": "ENTER_APP_ID"
            }
        }
  }';

// Update the IVR
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://public-api.sonetel.com/account/' . $acc_id . '/voiceapp' . '/' . $app_id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS => $json,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . trim($access_token),
        'Content-Type: application/json;charset=UTF-8'
    ),
));
$response = curl_exec($curl);
curl_close($curl);
$response = json_decode($response);

//Output
if ($response->{"status"} == "success") {
    echo ("IVR updated successfully");
} 
