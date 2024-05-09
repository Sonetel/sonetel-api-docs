<?php
// base urls
$base_url = "https://public-api.sonetel.com/";

// API Access token
$access_token = "ENTER_ACCESS_TOKEN";

// USER HAVE TO ENTER CALL_RECORDING_ID
$call_recording_id = "ENTER CALL_RECORDING_ID";

########################download call recordings####################

$curl_download_call_recordings = curl_init();

curl_setopt_array($curl_download_call_recordings, array(
CURLOPT_URL => $base_url.'call-recording/'.$call_recording_id.'?fields=file_access_details',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_CUSTOMREQUEST => 'GET',
CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.trim($access_token),
    'Content-Type: application/json'
),
));

$response_download_call_recordings = curl_exec($curl_download_call_recordings);
$response_download_call_recordings = json_decode($response_download_call_recordings);

curl_close($curl_download_call_recordings);

if($response_download_call_recordings->{'status'} == "success"){
    print_r ($response_download_call_recordings->{'response'}->{'file'}->{'file_access_details'}->{'url'});
}
else{
    print_r($response_download_call_recordings->{'response'});
}


