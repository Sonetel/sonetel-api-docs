<?php
// base urls
$base_url = "https://public-api.sonetel.com/";

// function will change the format of date
function date_format_change($date){
    return str_replace(":", "%3A", $date);
}

//Enter Date
$created_date_max = "ENTER THE MAX DATE";
date_format_change($created_date_max);

$created_date_min = "ENTER THE MIN DATE";
date_format_change($created_date_min);

// API Access token
$access_token = "ENTER_ACCESS_TOKEN";

// Sonetel account ID
$acc_id =  "ENTER_ACCOUNT_ID";

// #########################Choices for user######################

/*  type 1 to View recording
    type 2 to Download recording
    type 3 to Delete recording  */

$choice = 1; // Download Recording           

// ########################retrive all call recordings##################

function retrive_call_recordings($base_url, $acc_id, $access_token, $created_date_min, $created_date_max){
    $curl_retrive_call_recordings = curl_init();

    curl_setopt_array($curl_retrive_call_recordings, array(
    CURLOPT_URL => $base_url.'call-recording?account_id='.$acc_id.'&'.$created_date_min.'=&'.$created_date_max.'=&type=voice_call&fields=voice_call_details',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_POSTFIELDS => "<file contents here>",
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.trim($access_token),
        'Content-Type: application/json'
    ),
    ));

    $response_retrive_call_recordings = curl_exec($curl_retrive_call_recordings);

    curl_close($curl_retrive_call_recordings);
    echo $response_retrive_call_recordings;
}

if($choice==1){
    if(retrive_call_recordings($base_url, $acc_id, $access_token, $created_date_min, $created_date_max)){
        echo($response_retrive_call_recordings->{'response'});
    }
    
}

########################download call recordings####################

function download_call_recordings($base_url, $call_recording_id, $access_token){
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
    echo ($response_download_call_recordings->{'response'}->{'file'}->{'file_access_details'}->{'url'});

}

if($choice==2){
        // USER HAVE TO ENTER CALL_RECORDING_ID
        $call_recording_id = "ENTER CALL_RECORDING_ID";
        download_call_recordings($base_url, $call_recording_id, $access_token);
}    

############################delete recording#############################

function delete_recording($base_url,$del_rec_id, $access_token){
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

    if($response_delete_recording->{'status'} == "success"){
        return True;
    }
    else{
        return False;
    }
    curl_close($curl_delete_recording);
}

if($choice==3){
    $del_rec_id = "ENTER CALL_RECORDING_ID";  
    if(delete_recording($base_url, $del_rec_id, $access_token)==true){
        echo("Recording is successfully DELETED");
    }
}

