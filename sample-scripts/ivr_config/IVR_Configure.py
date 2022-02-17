import requests
import json
from base64 import b64encode

base_url = "https://public-api.sonetel.com/"
url_access_token = "https://api.sonetel.com/"

username = input("Enter your username:")
password = input("Enter the password:")

#Authentication of user / get an access token
payload_of_accesstoken={'grant_type': 'password',
'username': username,
'password': password,
'refresh': 'yes'}

#encoding username and pswd into base64 format
userAndPass = b64encode(b"sonetel-web:sonetel-web").decode("ascii")
basic_auth_header = { 'Authorization' : 'Basic %s' %  userAndPass }

gen_acc_token = requests.request("POST", "{}SonetelAuth/oauth/token".format(url_access_token),headers = basic_auth_header, data=payload_of_accesstoken).json()
access_token = gen_acc_token['access_token']


#account information  
headers = {"Authorization": "Bearer {}".format(access_token),
            'Content-Type': 'application/json'
            }

#account info API
api_acc_info =  requests.get('{}account/'.format(base_url),headers=headers).json()
acc_id = api_acc_info['response']["account_id"]
###################Get All Voice App By Id###################

response_all_ivr = requests.request("GET", "{}account/{}/voiceapp".format(base_url, acc_id), headers=headers).json()
print(response_all_ivr["response"])

###################Get All Voice App By Id###################

input_app_id = input("enter app_id from above script")
response_single_ivr = requests.request("GET", "{}account/{}/voiceapp/{}".format(base_url, acc_id, input_app_id), headers=headers).json()
response_single_ivr = response_single_ivr["response"]["app_id"]
# print(response_single_ivr)

##################Configure IVR########################

input_digit = input("on which digit you want to configure action") 

print("type 1 to connect IVR digit to  user""\n"
    "type 2 to connect IVR digit to phone number""\n"
    "type 3 to connect IVR digit to another IVR""\n")

input_action = input("Select Any Action")

#######################Digit action###############################
def action(input_action):
    global ph_var
    if input_action == 1:    
        ph_var= {
        "action": "call",
        "to": "phnum",
        "id": input("enter phone number which you wanto connect with IVR digit")
        }
        return ph_var
    if input_action == 2:
        ph_var= {
        "action": "call",
        "to": "other",
        "id": input("enter phone number which you wanto connect with IVR digit")
        }
        return ph_var
    if input_action == 3:
        ph_var= {
        "action": "connect",
        "to": "app",
        "id": input("enter app_id")
        }   
        
        return ph_var

def digit(input_digit,input_action):

    global menu_dict
    menu_dict = {}
    x = "digit_{}".format(input_digit)
    menu_dict[x] = action(int(input_action)) #calling func action
    print(menu_dict)

    global main_dict
    main_dict = {
    "app_type": "ivr",
    "play_menu": "yes",
    "name": "ivr",
    "voice": "en",
    "play_welcome": "yes",
    "get_extension": "yes",
    "menu": menu_dict
    }
    return(main_dict)

digit(input_digit,input_action)

update_ivr_payload = json.dumps(main_dict)
update_ivr_headers = {"Authorization": "Bearer {}".format(access_token),
            'Content-Type': 'application/json;charset=UTF-8'
            }
###############################################################
def update_ivr():
    global update_ivr_response
    update_ivr_response = requests.request("PUT", "{}account/{}/voiceapp/{}".format(base_url, acc_id, input_app_id), headers=update_ivr_headers, data=update_ivr_payload).json()

    if update_ivr_response["status"]=="success":
        return True
    else:
        return False  
if(update_ivr()):
    print("IVR updated successfully")
else:
    print("blahghhhhh")    
