// #  API Access token
var access_token = "ENTER_ACCESS_TOKEN"

// #  Phone numumber to purchase
var phnum = "ENTER_PHONE_NUMBER"

// # Sonetel account ID
var acc_id =  "ENTER_ACCOUNT_ID"

var request = require('request');
var options = {
  'method': 'POST',
  'url': 'https://public-api.sonetel.com/account/'+acc_id+'/phonenumbersubscription',
  'headers': {
    'Authorization': 'Bearer '+ access_token,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    "phnum": phnum
  })

};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});
