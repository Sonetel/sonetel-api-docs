from urllib import response
from click import password_option
import requests
import json
from base64 import b64encode
import phonenumbers

base_url = "https://public-api.sonetel.com/"
url_access_token = "https://api.sonetel.com/"
#your acc info
username = input("Enter your username:")
password = input("Enter the password:")

#convert into E164 format
def E164_format(number):
    number_parsed = phonenumbers.parse(number, None)  
    clean_phone = phonenumbers.format_number(number_parsed, phonenumbers.PhoneNumberFormat.E164)
    return(clean_phone)

#make a call
phone_1 = E164_format(input("Enter your phone number"))
phone_2 = E164_format(input("Enter the number whom you wish to call"))

#Authentication of user / get an access token
payload_of_accesstoken={'grant_type': 'password',
'username': username,
'password': password,
'refresh': 'yes'}
#encoding username and pswd into base64 format
userAndPass = b64encode(b"sonetel-web:sonetel-web").decode("ascii")
basic_auth_header = { 'Authorization' : 'Basic %s' %  userAndPass }




gen_acc_token = requests.request("POST", "{}SonetelAuth/beta/oauth/token".format(url_access_token),headers = basic_auth_header, data=payload_of_accesstoken).json()
access_token = gen_acc_token['access_token']

#account information  
headers = {"Authorization": "Bearer {}".format(access_token),
            'Cache-Control': '',
            'Content-Type': 'application/json;charset=UTF-8'
            }

#account info API
api_acc_info =  requests.get('{}account/'.format(base_url),headers=headers).json()
acc_id = api_acc_info['response']["account_id"]
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
    

    
