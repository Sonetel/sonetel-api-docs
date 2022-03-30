import requests
import json

base_url = "https://public-api.sonetel.com/"

# API Access token
access_token = "ENTER_ACCESS_TOKEN"

# Sonetel account ID
acc_id =  "ENTER_ACCOUNT_ID"

###################### New IVR inputs############################

ivr_name = "TYPE_NAME_OF_NEW_IVR"

#########################JSON####################################
json_data = json.dumps({
                "app_type": "ivr",
                "play_menu": "yes",
                "name": ivr_name,
                "voice": "en",
                "play_welcome": "yes",
                "get_extension": "yes",
                "menu": {
                        "digit_0":{
                            "action": "call",
                            "to": "user",
                            "id": "ENTER_USER_ID"
                        },
                        "digit_1":{
                            "action": "call",
                            "to": "phnum",
                            "id": "ENTER_PHONE_NUMBER"
                        },
                        "digit_2":{
                            "action": "connect",
                            "to": "app",
                            "id": "ENTER_APP_ID"
                        }   
                        }
            })


###########################Para########################################
headers = {"Authorization": "Bearer {}".format(access_token),
            'Content-Type': 'application/json;charset=UTF-8'
            }

################Create New IVR#####################
def create_new_ivr():
    response_create_ivr = requests.request("POST", "{}account/{}/voiceapp".format(base_url, acc_id), headers=headers, data=json_data).json()
    print(response_create_ivr)      
    if response_create_ivr["status"]=="success":
        return True
    else:
        return False    
if(create_new_ivr()):
    print("IVR Created successfully")

