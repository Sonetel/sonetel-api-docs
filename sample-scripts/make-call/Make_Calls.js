// PHONE NUMBERS
var phone_1 = "SENDER'S_PHONE_NUMBER";
var phone_2 = "RECEIVER'S_PHONE_NUMBER";

// API Access token
var access_token = "ENTER_ACCESS_TOKEN";

// Sonetel account ID
var acc_id =  "ENTER_ACCOUNT_ID";

//JSON
var obj = {
    app_id : acc_id, 
    call1 : phone_1, 
    call2 : phone_2, 
    show_1 : 'automatic', 
    show_2 : 'automatic'
  };
var json = JSON.stringify(obj);

//Make a Call
var request = require('request');
var options = {
  'method': 'POST',
  'url': 'https://public-api.sonetel.com/make-calls/call/call-back',
  'headers': {
    'Authorization': 'Bearer '+ access_token,
    'Cache-Control': '',
    'Content-Type': 'application/json;charset=UTF-8'
  },
  body: json
};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});
