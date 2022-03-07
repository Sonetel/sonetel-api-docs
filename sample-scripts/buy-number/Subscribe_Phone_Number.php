<?php

// #base urls
$base_url = "https://public-api.sonetel.com/";
$url_access_token = "https://api.sonetel.com/";
// #inputs
$username = readline('Enter your username: ');
$password = readline('Enter your password: ');
$country = readline("Enter the name of the nation whose number you wish to purchase:");

// #access token
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
// #json decode
$json_response_access_token = json_decode($response_access_token);
$access_token = $json_response_access_token->{'access_token'};

// #avail phone num
$conc_avail_phnum = $base_url."numberstocksummary/".$country."/availablephonenumber";
$curl_avail_phnum = curl_init();

curl_setopt_array($curl_avail_phnum, array(
  CURLOPT_URL => $conc_avail_phnum,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer '.trim($access_token),
  ),
));

// #response
$response_avail_phnum = curl_exec($curl_avail_phnum);
curl_close($curl_avail_phnum);

// #json
$json_response_avail_phnum = json_decode($response_avail_phnum);

// #phone num
$phnum = $json_response_avail_phnum->{'response'}[0]->{'phnum'};

// #charge of particular numb
$num_fee = $json_response_avail_phnum->{'response'}[0]->{'recurring_fee'};

// #account info
$curl_acc_info = curl_init();

curl_setopt_array($curl_acc_info, array(
  CURLOPT_URL => $base_url."account/",
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

// response
$response_acc_info = curl_exec($curl_acc_info);
curl_close($curl_acc_info);

$json_response_acc_info = json_decode($response_acc_info);
$acc_id =  $json_response_acc_info->{'response'}->{'account_id'};
// #credit balance in acc
$cred_bal = $json_response_acc_info->{'response'}->{'credit_balance'};

// #function to check balance
function checkBal($cred_bal, $num_fee){
  if ($cred_bal<$num_fee){
    echo("You do not have enough balance to purchase a number; thus, please add some balance to your account.");
    return false;
  } else {
    return True;
  }
}



// #if function returns true condition will run
if(checkBal($cred_bal, $num_fee)==True){
  // #subscribe phone num
// #phone number into json array
$json = array('phnum' => $phnum);
$json_data = json_encode($json);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $base_url."account/".$acc_id."/phonenumbersubscription",
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
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
$response_ouput = json_decode($response);
$stauts =  $json_response_acc_info->{'status'};
curl_close($curl);

if ($stauts=="success") {
  echo("you have subscribed ".$phnum);
}

}
