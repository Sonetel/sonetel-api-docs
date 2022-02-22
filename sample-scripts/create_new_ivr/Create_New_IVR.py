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
###################### New IVR inputs############################

ivr_name = input("Type some name for your new IVR")

#######################Digit action###############################
def action(input_action):
    global ph_var
    if input_action == 1:    
        ph_var= {
        "action": "call",
        "to": "user",
        "id": input("enter phone number which you want to connect with IVR digit")
        }
        return ph_var
    if input_action == 2:
        ph_var= {
        "action": "call",
        "to": "phnum",
        "id": input("enter phone number which you want to connect with IVR digit")
        }
        return ph_var
    if input_action == 3:
        ph_var= {
        "action": "connect",
        "to": "app",
        "id": input("enter app_id")
        }   
        
        return ph_var
        

#####################Append Dictionary#####################
def append_dict(menu_dict,input_digit,input_action):
    
    if -1<int(input_digit)<=9: #here it will ignore "n" -1 -1
        x = "digit_{}".format(input_digit)
        menu_dict[x] = action(int(input_action))#calling func action
        global dict
        dict = menu_dict
        print(dict)
        global main_dict
        main_dict = {
        "app_type": "ivr",
        "play_menu": "yes",
        "name": ivr_name,
        "voice": "en",
        "play_welcome": "yes",
        "get_extension": "yes",
        "menu": dict
        } 
    #condtion if user don't want to add any options
    elif input_digit==-1 and input_action==-1:
        main_dict = main_dict = {
        "app_type": "ivr",
        "play_menu": "yes",
        "name": ivr_name,
        "voice": "en",
        "play_welcome": "yes",
        "get_extension": "yes",
        "menu": dict
        } 
    return main_dict  

#######################################################################
menu_dict = {}

for count in range(9):
    yorn_input = input("press 'y' if you want to add options else 'n' ")
    if yorn_input == "y":
        input_digit = input("on which digit you want to configure action") 

        print("type 1 to connect IVR digit to  user""\n"
        "type 2 to connect IVR digit to phone number""\n"
        "type 3 to connect IVR digit to another IVR""\n")

        input_action = input("Select Any Action")
        append_dict(menu_dict,input_digit,input_action)
    elif yorn_input == "n":
        append_dict(menu_dict,-1,-1) 
        break

###########################Para########################################

create_ivr_payload = json.dumps(main_dict)
# print(create_ivr_payload)
create_ivr_headers = {"Authorization": "Bearer {}".format(access_token),
            'Content-Type': 'application/json;charset=UTF-8'
            }

################Create New IVR#####################
def create_new_ivr():
    response_create_ivr = requests.request("POST", "{}account/{}/voiceapp".format(base_url, acc_id), headers=create_ivr_headers, data=create_ivr_payload).json()
    print(response_create_ivr)      
    if response_create_ivr["status"]=="success":
        return True
    else:
        return False    
if(create_new_ivr()):
    print("IVR Created successfully")

