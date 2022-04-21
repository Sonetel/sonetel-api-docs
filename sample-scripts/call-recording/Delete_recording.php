<?php
// base urls
$base_url = "https://public-api.sonetel.com/";

// API Access token
$access_token = "ENTER_ACCESS_TOKEN";

// Sonetel account ID
$acc_id =  "ENTER_ACCOUNT_ID";

// Call Recording ID
$del_rec_id = "ENTER CALL_RECORDING_ID";  

############################delete recording#############################

$curl_delete_recording = curl_init();

curl_setopt_array($curl_delete_recording, array(
CURLOPT_URL => $base_url.'call-recording/'.$del_rec_id.'',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_CUSTOMREQUEST => 'DELETE',
CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.trim($access_token),
    'Content-Type: application/json'
),
));

$response_delete_recording = curl_exec($curl_delete_recording);
$response_delete_recording = json_decode($response_delete_recording);

curl_close($curl_delete_recording);

if($response_delete_recording->{'status'} == "success"){
    echo("Recording has been successfully DELETED");
}
else{
    print_r($response_delete_recording->{'response'});
}



