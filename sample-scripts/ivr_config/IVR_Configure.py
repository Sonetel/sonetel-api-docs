import requests
import json

base_url = "https://public-api.sonetel.com/"

# API Access token
access_token = "ENTER_ACCESS_TOKEN"

# Sonetel account ID
acc_id =  "ENTER_ACCOUNT_ID"

# Voice app ID
app_id = "ENTER_APP_ID"

#  SAMPLE JSON BODY
json = json.dumps({
    "app_type": "ivr",
    "play_menu": "yes",
    "name": "ENTER_IVR_NAME",
    "voice": "en",
    "play_welcome": "yes",
    "get_extension": "yes",
    "menu": {
        "digit_1": {
                "action": "call",
                "to": "phnum",
                "id": "ENTER_PHONE_NUMBER"
            },
        "digit_2": {
                "action": "call",
                "to": "user",
                "id": "ENTER_USER_ID"
            },
        "digit_3": {
                "action": "connect",
                "to": "app",
                "id": "ENTER_APP_ID"
            }
        }
  })

headers = {"Authorization": "Bearer {}".format(access_token),
            'Content-Type': 'application/json;charset=UTF-8'
            }
#  Update the IVR
def update_ivr():
    global update_ivr_response
    update_ivr_response = requests.request("PUT", "{}account/{}/voiceapp/{}".format(base_url, acc_id, app_id), headers=headers, data=json).json()

    if update_ivr_response["status"]=="success":
        return True
    else:
        return False  
if(update_ivr()):
    print("IVR updated successfully")

