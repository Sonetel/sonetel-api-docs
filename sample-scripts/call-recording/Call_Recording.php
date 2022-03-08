<?php
// base urls
$base_url = "https://public-api.sonetel.com/";
$url_access_token = "https://api.sonetel.com/";

// inputs
$username = readline('Enter your username: ');
$password = readline('Enter your password: ');

// function will change the format of date
function date_format_change($date){
    return str_replace(":", "%3A", $date);
}

$created_date_max = date_format_change(readline("Enter the max date"));
$created_date_min = date_format_change(readline("Enter the min date"));

// access token
$curl_access_token = curl_init();

curl_setopt_array($curl_access_token, array(
  CURLOPT_URL => $url_access_token.'SonetelAuth/oauth/token',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('grant_type' => 'password','username' => strval($username),'password' => strval($password),'refresh' => 'yes'),
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic c29uZXRlbC13ZWI6c29uZXRlbC13ZWI='
  ),
));

$response_access_token = curl_exec($curl_access_token);
curl_close($curl_access_token);
// #json
$json_response_access_token = json_decode($response_access_token);
$access_token = $json_response_access_token->{'access_token'};

// account info
$curl_acc_info = curl_init();

curl_setopt_array($curl_acc_info, array(
  CURLOPT_URL =>  $base_url."account/",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.trim($access_token),
    'Content-Type: application/json'
  ),
));

$response_acc_info = curl_exec($curl_acc_info);
curl_close($curl_acc_info);

// json
$json_response_acc_info = json_decode($response_acc_info);
$acc_id =  $json_response_acc_info->{'response'}->{'account_id'};

// #Choices for user
echo("type 1 to View recording\n
    type 2 to Download recording\n
    type 3 to Delete recording");

$choice = (int)(readline("Enter your choice"));

// ########################retrive all call recordings##################

function retrive_call_recordings($base_url, $acc_id, $access_token, $created_date_min, $created_date_max){
    $curl_retrive_call_recordings = curl_init();

    curl_setopt_array($curl_retrive_call_recordings, array(
    CURLOPT_URL => $base_url.'call-recording?account_id='.$acc_id.'&'.$created_date_min.'=&'.$created_date_max.'=&type=voice_call&fields=voice_call_details',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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

if($choice==1 or 2 or 3){
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
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.trim($access_token),
        'Content-Type: application/json'
    ),
    ));

    $response_download_call_recordings = curl_exec($curl_download_call_recordings);
    $response_download_call_recordings = json_decode($response_download_call_recordings);
    echo ($response_download_call_recordings->{'response'}->{'file'}->{'file_access_details'}->{'url'});
    curl_close($curl_download_call_recordings);
}

if($choice==2){
        // #user have to enter call_recording_id
        $call_recording_id = readline("copy and paste call_recording_id from above list");
        download_call_recordings($base_url, $call_recording_id, $access_token);
}    


############################delete recording#############################

function delete_recording($base_url,$del_rec_id, $access_token){
    $curl_delete_recording = curl_init();

    curl_setopt_array($curl_delete_recording, array(
    CURLOPT_URL => $base_url.'call-recording/'.$del_rec_id.'',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
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
    //  calling so user can see their call rec
    // retrive_call_recordings();
    $del_rec_id = readline("copy and paste call_id from above list which you want delete");  
    if(delete_recording($base_url, $del_rec_id, $access_token)==true){
        echo("Recording is successfully DELETED");
    }
}

