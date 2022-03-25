import requests
import json

base_url = "https://public-api.sonetel.com/"

#your acc info
username = "ENTER_YOUR_USERNAME:"
password = "ENTER_THE_PASSWORD:"

#make a call
phone_1 = "SENDER'S_PHONE_NUMBER"
phone_2 = "RECEIVER'S_PHONE_NUMBER"

# access token
access_token = "ENTER_ACCESS_TOKEN"

# Sonetel account ID
acc_id =  "ENTER_ACCOUNT_ID"

#headers and payload
headers = {"Authorization": "Bearer {}".format(access_token),
            'Cache-Control': '',
            'Content-Type': 'application/json;charset=UTF-8'
            }
payload =json.dumps({
  "app_id": acc_id,
  "call1": phone_1,
  "call2": phone_2,
  "show_1": "automatic",
  "show_2": "automatic"
})

#make a call
def make_call():
    response = requests.request("POST", "{}make-calls/call/call-back".format(base_url), headers=headers, data=payload).json()
    if response['statusCode'] == 202:
        return True
    else:
        return False    

if (make_call()):
    print("Call Accepted")
    

    