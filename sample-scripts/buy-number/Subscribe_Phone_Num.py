"""
Sample script to purchase a phone number.
Use the /availablephonenumber endpoint to get a list of numbers available for purchase

Read more at https://docs.sonetel.com
"""
import json
import requests
import jwt

# Numumber to purchase
phnum = "ENTER_PHONE_NUMBER"

base_url = "https://public-api.sonetel.com/"

#  API Access token
access_token = "ENTER_ACCESS_TOKEN"
decoded_token = jwt.decode(
    access_token,
    audience='api.sonetel.com',
    options={"verify_signature": False}
    )
acc_id = decoded_token['acc_id']

# Request headers
headers = {
    "Authorization": "Bearer {}".format(access_token),
    'Content-Type': 'application/json'
    }

# Purchase phone number
response = requests.request(
    "POST",
    '{}account/{}/phonenumbersubscription'.format(base_url,acc_id),
    headers=headers,
    data=json.dumps({"phnum": phnum}), timeout=60)

response.raise_for_status()

if response.ok:
    print(response.json())
