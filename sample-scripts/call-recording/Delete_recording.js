// API Access token
var access_token = "ENTER_ACCESS_TOKEN";

// Call Recording ID
var del_rec_id = "ENTER CALL_RECORDING_ID";  

var request = require('request');
var options = {
  'method': 'DELETE',
  'url': 'https://public-api.sonetel.com/call-recording/'+del_rec_id,
  'headers': {
    'Authorization': 'Bearer '+access_token,
    'Content-Type': 'application/json'
  }
};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});
