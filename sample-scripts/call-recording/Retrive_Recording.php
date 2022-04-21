<?php
// base urls
$base_url = "https://public-api.sonetel.com/";

//Enter Date
$created_date_max = "yyyy-mm-ddThh:mm:ssZ";
urlencode($created_date_max);

$created_date_min = "yyyy-mm-ddThh:mm:ssZ";
urlencode($created_date_min);

// API Access token
$access_token = "ENTER_ACCESS_TOKEN";

// Sonetel account ID
$acc_id =  "ENTER_ACCOUNT_ID";

// ########################retrive all call recordings##################

$curl_retrive_call_recordings = curl_init();

curl_setopt_array($curl_retrive_call_recordings, array(
CURLOPT_URL => $base_url.'call-recording?account_id='.$acc_id.'&'.$created_date_min.'=&'.$created_date_max.'=&type=voice_call&fields=voice_call_details',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_CUSTOMREQUEST => 'GET',
CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.trim($access_token),
    'Content-Type: application/json'
),
));

$response_retrive_call_recordings = curl_exec($curl_retrive_call_recordings);
$response_retrive_call_recordings = json_decode($response_retrive_call_recordings);


curl_close($curl_retrive_call_recordings);

if($response_retrive_call_recordings->{'status'} == "success"){
    print_r($response_retrive_call_recordings->{'response'});
}
else{
    print_r($response_retrive_call_recordings);
}
