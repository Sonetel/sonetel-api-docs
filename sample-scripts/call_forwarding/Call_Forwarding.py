from sre_constants import SUCCESS
import requests
import json
from base64 import b64encode

base_url = "https://public-api.sonetel.com/"
url_access_token = "https://api.sonetel.com/"

username = input("Enter your username:")
password = input("Enter the password:")

#access token
access_token = input("Enter the Access Token")

#account information  
headers = {"Authorization": "Bearer {}".format(access_token),
            'Content-Type': 'application/json'
            }

#account info API
api_acc_info =  requests.get('{}account/'.format(base_url),headers=headers).json()
acc_id = api_acc_info['response']["account_id"]

#forwarde phone number
fwd_phn_num =input("Enter phone number") #Your phone number in the E164 format without the leading +.


#Choices for user
# print(#"type 1 to change call forwarding to user""\n"
#     "type 1 to change call forwarding to mobile number""\n"
#     "type 2 to change call forwarding to SIP URL""\n"
#     "type 3 to change call forwarding to voice app""\n")
choice = int(1) #to mobile number


########################payload for forwarding##################
# if(choice==1):
#     connect_to_type = "user"
#     connect_to = input("Enter the user_id")
#     payload_fwd =json.dumps({
#   "connect_to_type": connect_to_type,
#   "connect_to": connect_to
# })

if(choice==1):
    connect_to_type = "phnum"
    connect_to = input("Enter the phone number")
    payload_fwd =json.dumps({
      
  "connect_to_type": connect_to_type,
  "connect_to": connect_to
})

elif(choice==2):
    connect_to_type = "sip"
    connect_to = input("Enter the sip url")
    payload_fwd =json.dumps({
  "connect_to_type": connect_to_type,
  "connect_to": connect_to
})

elif(choice==3):
    response_appid = requests.request("GET", "{}account/{}/voiceapp".format(base_url ,acc_id), headers=headers).json()

    for value in response_appid["response"]:
        print(value["name"], ":", value["app_id"])

    app_id = input("Enter the ONLY app_id from above list:")
    connect_to_type = "app"
    payload_fwd =json.dumps({
  "connect_to_type": connect_to_type,
  "connect_to": {
    "app_type": "ivr",
    "app_id": app_id
  }
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
