import requests
import json
from base64 import b64encode

#inputs
username = input("Enter your username:")
password = input("Enter the password:")
country = input("Enter the name of the country:")

#generate access token
payload_of_accesstoken={'grant_type': 'password',
'username': username,
'password': password,
'refresh': 'yes'}
userAndPass = b64encode(b"sonetel-web:sonetel-web").decode("ascii")
basic_auth_header = { 'Authorization' : 'Basic %s' %  userAndPass }
gen_acc_token = requests.request("POST", f"https://chat-api.sonetel.com/SonetelAuth/1.3/oauth/token",headers = basic_auth_header, data=payload_of_accesstoken).json()
access_token = gen_acc_token['access_token']

#account information  
headers = {"Authorization": "Bearer {}".format(access_token),
            'Content-Type': 'application/json'
            }
#account info API
api_acc_info =  requests.get(f'https://chat-api.sonetel.com/account/',headers=headers).json()
acc_id = api_acc_info['response']["account_id"]

# print(api_acc_info)
cred_bal = api_acc_info['response']['credit_balance']
# print(cred_bal)

#This will check whether or not the account has a sufficient balance.
def check_enough_bal():

    #available phone number API
    avail_numb_country =  requests.get(f'https://chat-api.sonetel.com/numberstocksummary/{country}/availablephonenumber',headers=headers).json()
    num_fee = avail_numb_country['response'][0]['recurring_fee']
    global phone_number
    phone_number = json.dumps({
                        "phnum": avail_numb_country['response'][0]['phnum'],
                        })
    if cred_bal < num_fee:
        print("You do not have enough balance to purchase a number; thus, please add some balance to your account.")
        return False
    else:
        return True 
        
#If the amount is adequate, it will purchase a phone number.
if(check_enough_bal()):

    #purchase phone number API
    buy_number = requests.request("POST", f'https://chat-api.sonetel.com/account/{acc_id}/phonenumbersubscription', headers=headers, data=phone_number).json()
    print("SUCCESS,you have purchased {}".format(phone_number))