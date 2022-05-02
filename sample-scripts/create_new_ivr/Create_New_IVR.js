// API Access token
var access_token = "ENTER_ACCESS_TOKEN";

// Sonetel account ID
var acc_id =  "ENTER_ACCOUNT_ID";

// New IVR inputs

ivr_name = "TYPE_NAME_OF_NEW_IVR";

var json_data = {
    "app_type": "ivr",
    "play_menu": "yes",
    "name": ivr_name,
    "voice": "en",
    "play_welcome": "yes",
    "get_extension": "yes",
    "menu": {
            "digit_0":{
                "action": "call",
                "to": "user",
                "id": "ENTER_USER_ID"
            },
            "digit_1":{
                "action": "call",
                "to": "phnum",
                "id": "ENTER_PHONE_NUMBER"
            },
            "digit_2":{
                "action": "connect",
                "to": "app",
                "id": "ENTER_APP_ID"
            }   
            }
};

var request = require('request');
var options = {
  'method': 'POST',
  'url': 'https://public-api.sonetel.com/account/'+acc_id+'/voiceapp',
  'headers': {
    'Authorization': 'Bearer '+access_token,
    'Content-Type': 'application/json;charset=UTF-8'
  },
  body: JSON.stringify(json_data)

};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});
