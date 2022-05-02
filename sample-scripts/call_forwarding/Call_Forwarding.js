// #  API Access token
var access_token = "ENTER_ACCESS_TOKEN";

// #forwarde phone number
var fwd_phn_num ="ENTER PHONE NUMBER *DONT USE +*"; //Your phone number in the E164 format without the leading +.

// #  Sonetel account ID
var acc_id =  "ENTER_ACCOUNT_ID";

payload_fwd ={
    "connect_to_type": "ENTER_TYPE",
    "connect_to": "ENTER_PARA" 
  };

var request = require('request');
var options = {
  'method': 'PUT',
  'url': 'https://public-api.sonetel.com/account/'+acc_id+'/phonenumbersubscription/'+fwd_phn_num,
  'headers': {
    'Authorization': 'Bearer '+access_token,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload_fwd)

};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});
