<?php

// PHONE NUMBERS
$phone_1 = "SENDER'S_PHONE_NUMBER";
$phone_2 = "RECEIVER'S_PHONE_NUMBER";

// API Access token
$access_token = "ENTER_ACCESS_TOKEN";

// Sonetel account ID
$acc_id =  "ENTER_ACCOUNT_ID";

// SAMPLE JSON BODY
$json = array(
  'app_id' => $acc_id, 
  'call1' => $phone_1, 
  'call2' => $phone_2, 
  'show_1' => 'automatic', 
  'show_2' => 'automatic');
$json_data = json_encode($json);

//Make a Call
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://public-api.sonetel.com/make-calls/call/call-back',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>$json_data,
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer '.trim($access_token),
      'Cache-Control: ',
      'Content-Type: application/json;charset=UTF-8'
    ),
));
$response = curl_exec($curl);
curl_close($curl);
$response = json_decode($response);

//Output
if ($response->{'statusCode'} == "202") {
  echo("Call Accepted");
} 

