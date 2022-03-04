<?php
// base urls
$base_url = "https://public-api.sonetel.com/";
$url_access_token = "https://api.sonetel.com/";

// inputs
$username = readline('Enter your username: ');
$password = readline('Enter your password: ');

$phone_1 = readline("Enter your phone number");
$phone_2 = readline("Enter the number whom you wish to call");

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

#json
$json_response_acc_info = json_decode($response_acc_info);
$acc_id =  $json_response_acc_info->{'response'}->{'account_id'};

// make a call
$curl_make_call = curl_init();
#json array of header
$json = array(
  'app_id' => $acc_id, 
  'call1' => $phone_1, 
  'call2' => $phone_2, 
  'show_1' => 'automatic', 
  'show_2' => 'automatic');
$json_data = json_encode($json);

curl_setopt_array($curl_make_call, array(
  CURLOPT_URL => $base_url.'make-calls/call/call-back',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$json_data,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.trim($access_token),
    'Cache-Control: ',
    'Content-Type: application/json;charset=UTF-8'
  ),
));

$response_make_call = curl_exec($curl_make_call);
curl_close($curl_make_call);
#json
$json_response_make_call = json_decode($response_make_call);
$status =  $json_response_make_call->{'statusCode'};


if($status == "202"){
  echo("Call Accepted");
}
