// #  API Access token
var access_token = "ENTER_ACCESS_TOKEN";

// # Sonetel account ID
var acc_id =  "ENTER_ACCOUNT_ID";

// # Voice app ID
app_id = "ENTER_APP_ID";

var json_data = {
    "app_type": "ivr",
    "play_menu": "yes",
    "name": "ENTER_IVR_NAME",
    "voice": "en",
    "play_welcome": "yes",
    "get_extension": "yes",
    "menu": {
        "digit_1": {
                "action": "call",
                "to": "phnum",
                "id": "ENTER_PHONE_NUMBER"
            },
        "digit_2": {
                "action": "call",
                "to": "user",
                "id": "ENTER_USER_ID"
            },
        "digit_3": {
                "action": "connect",
                "to": "app",
                "id": "ENTER_APP_ID"
            }
        }
  };

var request = require('request');
var options = {
  'method': 'PUT',
  'url': 'https://public-api.sonetel.com/account/'+acc_id+'/voiceapp/'+app_id,
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
