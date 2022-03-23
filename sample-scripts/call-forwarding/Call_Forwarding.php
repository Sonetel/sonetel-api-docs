<?php
// base urls
$base_url = "https://public-api.sonetel.com/";

// API Access token
$access_token = "ENTER_ACCESS_TOKEN";

// Sonetel account ID
$acc_id =  "ENTER_ACCOUNT_ID";

// #forwarde phone number
$fwd_phn_num ="ENTER PHONE NUMBER *DONT USE +*"; //Your phone number in the E164 format without the leading +.

$payload_fwd = '{
  "connect_to_type": "ENTER_TYPE",
  "connect_to": "ENTER_PARA" 
}';
// ########################forwared call####################

$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => $base_url.'account/'.$acc_id.'/phonenumbersubscription/'.$fwd_phn_num,
CURLOPT_CUSTOMREQUEST => 'PUT',
CURLOPT_POSTFIELDS =>$payload_fwd,
CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.trim($access_token),
    'Content-Type: application/json'
),
));

$response = curl_exec($curl);
curl_close($curl);
$response = json_decode($response);

if ($response->{"status"}=="success"){
  echo("call forwarded successfully");
}
