//Enter Date
var created_date_max = "yyyy-mm-ddThh:mm:ssZ";
var created_date_max = encodeURIComponent($created_date_max);

var created_date_min = "yyyy-mm-ddThh:mm:ssZ";
var created_date_max = encodeURIComponent($created_date_min);

// API Access token
var access_token = "ENTER_ACCESS_TOKEN";

// Sonetel account ID
var acc_id =  "ENTER_ACCOUNT_ID";

var request = require('request');
var options = {
  'method': 'GET',
  'url': 'https://public-api.sonetel.com/call-recording?account_id='+acc_id+'&created_date_min='+created_date_min+'&created_date_max='+created_date_max+'&type=voice_call&fields=voice_call_details',
  'headers': {
    'Authorization': 'Bearer '+access_token,
    'Content-Type': 'application/json'
  },
};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});
