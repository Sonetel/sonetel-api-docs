// API Access token
var access_token = "ENTER_ACCESS_TOKEN";

// USER HAVE TO ENTER CALL_RECORDING_ID
var call_recording_id = "ENTER CALL_RECORDING_ID";

var request = require('request');
var options = {
  'method': 'GET',
  'url': 'https://public-api.sonetel.com/call-recording/'+call_recording_id+'?fields=file_access_details',
  'headers': {
    'Authorization': 'Bearer '+access_token,
    'Content-Type': 'application/json'
  }
};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});
