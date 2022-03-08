<?php
// base urls
$base_url = "https://public-api.sonetel.com/";
$url_access_token = "https://api.sonetel.com/";

// inputs
$username = readline('Enter your username: ');
$password = readline('Enter your password: ');

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

// #json
$json_response_acc_info = json_decode($response_acc_info);
$acc_id =  $json_response_acc_info->{'response'}->{'account_id'};

// #forwarde phone number
$fwd_phn_num =readline("Enter phone number *DONT USE +*"); //Your phone number in the E164 format without the leading +.


// #Choices for user
echo("type 1 to change call forwarding to user\n
    type 2 to change call forwarding to mobile number\n
    type 3 to change call forwarding to SIP URL\n
    type 4 to change call forwarding to voice app\n");
$choice = (int)(readline("Enter your choice"));

// ########################payload for forwarding##################
if($choice==1){
    $connect_to_type = "user";
    $connect_to = readline("Enter the user_id\n");
    $json = array('connect_to_type' => $connect_to_type, 'connect_to' => $connect_to);
    $payload_fwd = json_encode($json);
    // echo $payload_fwd;
}

else if($choice==2){
    $connect_to_type = "phnum";
    $connect_to = readline("Enter the phone number\n");
    $json = array('connect_to_type' => $connect_to_type, 'connect_to' => $connect_to);
    $payload_fwd = json_encode($json);
    // echo $payload_fwd;

}

else if($choice==3){
    $connect_to_type = "sip";
    $connect_to = readline("Enter the sip url\n");
    $json = array('connect_to_type' => $connect_to_type, 'connect_to' => $connect_to);
    $payload_fwd = json_encode($json);
    // echo $payload_fwd;

}
else if($choice==4){
  // get all voice apps
  $curl_all_voiceapps = curl_init();

  curl_setopt_array($curl_all_voiceapps, array(
    CURLOPT_URL => $base_url.'account/'.$acc_id.'/voiceapp',
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

  $response_all_voiceapps = curl_exec($curl_all_voiceapps);

  curl_close($curl_all_voiceapps);
  // echo $response_all_voiceapps;

  $json_response_all_voiceapps = json_decode($response_all_voiceapps);
  // $app_id =  $json_response_all_voiceapps->{'response'}[0]->{'app_id'};

  $array = [];

  //print all voice app and name
  foreach ($json_response_all_voiceapps->{'response'} as $reponseItem){
      echo ($reponseItem->{'name'}.'->'.$reponseItem->{'app_id'});
      echo "<\n>";
    }
  
  // get voice app by id 
  $app_id = readline("Enter the ONLY app_id from above list ");
  $connect_to_type = "app";
  $json = array('connect_to_type' => $connect_to_type, 'connect_to' => array("app_type"=> "ivr","app_id"=> $app_id));
  $payload_fwd = json_encode($json);
  // echo $payload_fwd;
}
// ########################forwared call####################
function forward_call($base_url,$acc_id, $fwd_phn_num,$payload_fwd, $access_token){

    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => $base_url.'account/'.$acc_id.'/phonenumbersubscription/'.$fwd_phn_num,//$base_url.'account/'.$acc_id.'/phonenumbersubscription/'.$fwd_phn_num,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS =>$payload_fwd,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer '.trim($access_token),
        'Content-Type: application/json'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    // echo $response;
    $response = json_decode($response);

    $status = $response->{"status"};
    if ($status=="success"){
      return True;
    }
    else{
      return False;
    }
}
// main condtion and function
if(forward_call($base_url, $acc_id, $fwd_phn_num,$payload_fwd, $access_token)){
    echo("call forwarded successfully");
}