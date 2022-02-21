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

######################################################################

#######################Digit action###############################
def action(input_action):
    global ph_var
    if input_action == 1:    
        ph_var= {
        "action": "call",
        "to": "user",
        "id": input("enter phone number which you wanto connect with IVR digit")
        }
        return ph_var
    if input_action == 2:
        ph_var= {
        "action": "call",
        "to": "phnum",
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
        

###################################create ivr#######
def create_ivr():
    input_digit = input("on which digit you want to configure action") 

    print("type 1 to connect IVR digit to  user""\n"
    "type 2 to connect IVR digit to phone number""\n"
    "type 3 to connect IVR digit to another IVR""\n")

    input_action = input("Select Any Action")
    

# def digit(input_digit,input_action):

    global menu_dict
    x = "digit_{}".format(input_digit)
    menu_dict[x] = action(int(input_action))#calling func action
    print(menu_dict)
    local_dict = {
    "app_type": "ivr",
    "play_menu": "yes",
    "name": ivr_name,
    "voice": "en",
    "play_welcome": "yes",
    "get_extension": "yes",
    "menu": {}
    }    
#######################################################################

count = 0
for count in range(9):
    yorn_input = input("press 'y' if you want to add options else 'n' ")
    if yorn_input == "y":
        create_ivr()
        count+=1
    elif yorn_input == "n":
        break    

           
           
