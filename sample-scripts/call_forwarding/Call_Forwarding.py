from sre_constants import SUCCESS
import requests
import json

base_url = "https://public-api.sonetel.com/"

#  API Access token
access_token = "ENTER_ACCESS_TOKEN"

#forwarde phone number
fwd_phn_num ="ENTER PHONE NUMBER *DONT USE +*"; #Your phone number in the E164 format without the leading +.

#  Sonetel account ID
acc_id =  "ENTER_ACCOUNT_ID"

#Choices for user
# "type 1 to change call forwarding to mobile number""\n"
# "type 2 to change call forwarding to SIP URL""\n"
# "type 3 to change call forwarding to voice app""\n"
choice = 1

#account information  
headers = {"Authorization": "Bearer {}".format(access_token),
            'Content-Type': 'application/json'
            }

payload_fwd = json.dumps({
  "connect_to_type": "ENTER_TYPE",
  "connect_to": "ENTER_PARA" 
})

########################forwared call####################
def forward_call():
  response = requests.request("PUT", "{}account/{}/phonenumbersubscription/{}".format(base_url, acc_id, fwd_phn_num), headers=headers, data=payload_fwd).json()
  status = response["status"] 
  if status=="success":
    return True
  else:
    return False  
if(forward_call()):
  print("call forwarded successfully")
