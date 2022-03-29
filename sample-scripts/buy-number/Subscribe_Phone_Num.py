import requests
import json

base_url = "https://public-api.sonetel.com/"

#  API Access token
access_token = "ENTER_ACCESS_TOKEN"

#  Phone numumber to purchase
phnum = "ENTER_PHONE_NUMBER"
phnum = json.dumps({"phnum": phnum})

# Sonetel account ID
acc_id =  "ENTER_ACCOUNT_ID"

#account information  
headers = {"Authorization": "Bearer {}".format(access_token),
            'Content-Type': 'application/json'
            }

#purchase phone number API
response = requests.request("POST", '{}account/{}/phonenumbersubscription'.format(base_url,acc_id), headers=headers, data=phnum).json()
if response['status']=="success":
    print("SUCCESS,you have purchased {}".format(phnum))