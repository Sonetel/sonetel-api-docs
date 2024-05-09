<?php

// API Access token
$access_token = "ENTER_ACCESS_TOKEN";

// Phone numumber to purchase
$phnum = "ENTER_PHONE_NUMBER";

// Sonetel account ID
$acc_id =  "ENTER_ACCOUNT_ID";

// Subscribe Phone Num
$json = array('phnum' => $phnum);
$json = json_encode($json);

//Subscibe
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://public-api.sonetel.com/account/'.$acc_id.'/phonenumbersubscription',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$json,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . trim($access_token),
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);
$response = json_decode($response);
curl_close($curl);

//Output
if ($response->{'status'} == "success") {
    echo ("$phnum added to your account.");
}
else{
    echo($response->{'response'});
}