import requests

base_url = "https://public-api.sonetel.com/"

# Function will change the format of date
def date_format_change(date):
    date.replace(":", "%3A")

# Enter Date
created_date_max = "ENTER THE MAX DATE"
date_format_change(created_date_max)

created_date_min = "ENTER THE MIN DATE"
date_format_change(created_date_min)

#  API Access token
access_token = "ENTER_ACCESS_TOKEN"

#  Sonetel account ID
acc_id =  "ENTER_ACCOUNT_ID"

#account information  
headers = {"Authorization": "Bearer {}".format(access_token),
            'Content-Type': 'application/json'
            }
            
#########################Choices for user######################

#     type 1 to View recording
#     type 2 to Download recording
#     type 3 to Delete recording  

choice = 1; # Download Recording           

########################retrive all call recordings##################

def retrive_call_recordings():
    global response_all_recording
    response_all_recording= requests.request("GET", "{}call-recording?account_id={}&created_date_min={}&created_date_max={}&type=voice_call&fields=voice_call_details".format(base_url,acc_id,created_date_min,created_date_max), headers=headers).json()
    return response_all_recording
if(choice==1):
    if(retrive_call_recordings()):
        print(response_all_recording["response"])

########################download call recordings####################

def download_call_recordings():
    global response_download_recording
    response_download_recording = requests.request("GET", "{}call-recording/{}?fields=file_access_details".format(base_url, call_recording_id), headers=headers).json()
    return response_download_recording
if(choice==2):    
    #user have to enter call_recording_id
    call_recording_id = "ENTER CALL_RECORDING_ID"
    if(download_call_recordings()):
        print(response_download_recording["response"]["file"]["file_access_details"]["url"])

############################delete recording#############################

def delete_recording():
    global response_delete_recording
    response_delete_recording = requests.request("DELETE", "{}call-recording/{}".format(base_url, del_rec_id), headers=headers).json()
    if response_delete_recording['status'] == "success":
         return True
    else:
         return False  
if(choice==3):  
    # calling so user can see their call rec
    retrive_call_recordings()
    del_rec_id = "ENTER CALL_RECORDING_ID" 
    if(delete_recording()):
            print("Recording is successfully DELETED")

