<?php
// base urls
$base_url = "https://public-api.sonetel.com/";
$url_access_token = "https://api.sonetel.com/";

// inputs
$username = "ENTER_USERNAME";//"hpt3@xpathy.net"; //<-
$password = "ENTER_PASSWORD";//"harshpatel"; //<-

// access token
$access_token = "ENTER_ACCESS_TOKEN";//<-

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

//appid
$app_id = "ENTER_APP_ID";//"VAdffyzuoiog"; //<-

// Configure IVR
$input_digit = "Enter the digit on which you want to configure action"; // for e.g. 2 //<-

//
"type 1 to connect IVR digit to  user";
"type 2 to connect IVR digit to phone number";
"type 3 to connect IVR digit to another IVR";

$input_action = "Select Any Action";// for e.g. 2 //<-

#######################Digit action###############################
function action($input_action){
    if($input_action==1){
        $ph_var = array(
            'action' => 'call', 
            'to' => 'user', 
            'id' => "ENTER_USER_ID", //<-
            );
        return $ph_var;
    }    
    if($input_action==2){
        $ph_var = array(
            'action' => 'call', 
            'to' => 'phnum', 
            'id' =>"ENTER_PHONE_NUMBER", //"+917045891836" //<-
            );
        return $ph_var;
    }

    if($input_action==3){
        $ph_var = array(
            'action' => 'connect', 
            'to' => 'app', 
            'id' => "ENTER_APP_ID",//<-
            );
        return $ph_var;
    }
  }
// ###################Get single Voice App By Id###################

$curl_single_ivr = curl_init();

curl_setopt_array($curl_single_ivr, array(
  CURLOPT_URL => 'https://public-api.sonetel.com/account/'.$acc_id.'/voiceapp'.'/'.$app_id,
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

$response_single_ivr = curl_exec($curl_single_ivr);

curl_close($curl_single_ivr);

#json
$json_response_single_ivr = json_decode($response_single_ivr);

///////////////////////Create Dictionary/Menu creation////////////////////////////

function digit($input_action, $input_digit, $json_response_single_ivr){
    $ph_var = action($input_action);
    $x = "digit_".$input_digit;
    $menu_dict =array(
    $x => $ph_var,
    );

    $main_dict = [
      "app_type"=> $json_response_single_ivr->{"response"}->{"app_type"},
      "play_menu"=> $json_response_single_ivr->{"response"}->{"play_menu"},
      "name"=> $json_response_single_ivr->{"response"}->{"name"},
      "voice"=> $json_response_single_ivr->{"response"}->{"voice"},
      "play_welcome"=> $json_response_single_ivr->{"response"}->{"play_welcome"},
      "get_extension"=> $json_response_single_ivr->{"response"}->{"get_extension"},
      "menu"=> $menu_dict,
    ];
    $main_dict = json_encode($main_dict);
    return $main_dict;
  
}

$dict = digit($input_action, $input_digit, $json_response_single_ivr);
///////////Update IVR//////////////

function update_ivr($acc_id, $app_id, $dict, $access_token){
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://public-api.sonetel.com/account/'.$acc_id.'/voiceapp'.'/'.$app_id,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'PUT',
    CURLOPT_POSTFIELDS =>$dict,
    CURLOPT_HTTPHEADER => array(
      'Authorization: Bearer '.trim($access_token),
      'Content-Type: application/json;charset=UTF-8'
    ),
  ));
  
  $response = curl_exec($curl);
  // json decode
  curl_close($curl);
  $response = json_decode($response);  
  $status = $response->{"status"};
    if ($status=="success"){
      return True;
    }
    else{
      return False;
    }
}
if(update_ivr($acc_id, $app_id, $dict, $access_token)){
  echo("IVR updated successfully");
}
