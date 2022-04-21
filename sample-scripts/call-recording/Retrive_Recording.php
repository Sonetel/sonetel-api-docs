<?php
// base urls
$base_url = "https://public-api.sonetel.com/";

//Enter Date
$created_date_max = "2022-12-12T12:12:12Z";
urlencode($created_date_max);

$created_date_min = "2021-12-12T12:12:12Z";
urlencode($created_date_min);

// API Access token
$access_token = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdWQiOiJhcGkuc29uZXRlbC5jb20iLCJ1c2VyX2lkIjoiMjAxNjU0NzQ5MSIsInVzZXJfbmFtZSI6ImhwdDNAeHBhdGh5Lm5ldCIsInNjb3BlIjpbImFjY291bnQucmVhZCIsImFjY291bnQud3JpdGUiLCJjb252ZXJzYXRpb24ucmVhZCIsImNvbnZlcnNhdGlvbi53cml0ZSIsInVzZXIucmVhZCIsInVzZXIud3JpdGUiXSwiaXNzIjoiU29uZXRlbE5vZGUxMjMiLCJleHAiOjE2NTA2MDY5MzYsImlhdCI6MTY1MDUyMDUzNiwianRpIjoiM2I2ZjgwMGQtMGIyMS00NjhhLWIwZWItNmU2YmM0NDZmNTEwIiwiYWNjX2lkIjoyMDc5MTI0NjUsImNsaWVudF9pZCI6InNvbmV0ZWwtd2ViIn0.cz_X1ZIgU1LP9oxM5_9axRL0Y-nX_U6YkIAcdXzBSjPFTar-pjda7HEWCVE0bf6i3dKqiO9F9EzAlv7NcEPENavOuK9kC-SzGzjV7X25eADRCmcGuwhnJEKJDva9BMWBHeULcwIpGKrHlmlJyvkXISbrMHR0N8eLNqKylTSWS2i4L0Pucfxw1qtoJD51x2Jospheuc8CXGQNdmqDbkKo7jYRmxbVmJ1h7wWvywvAdXlmPO0z0WIA9JybVnrvnr8rH4qNB_1DC25_feNanrpjxqzkikWlH6iYOMETYPThI9NMnVUt2bHcNnIonLhvbhgu4wQ-bWijGyuN6_l4USuNwQ";

// Sonetel account ID
$acc_id =  "207943264";

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
