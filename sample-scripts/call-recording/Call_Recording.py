import requests
import json
from base64 import b64encode

base_url = "https://public-api.sonetel.com/"
url_access_token = "https://api.sonetel.com/"

username = input("Enter your username:")
password = input("Enter the password:")

#function will change the format of date
def date_format_change(date):
    date.replace(":", "%3A")

created_date_max = input("Enter the max date")
date_format_change(created_date_max)
created_date_min = input("Enter the min date")
date_format_change(created_date_min)

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

#Choices for user
print("type 1 to View recording""\n"
    "type 2 to Download recording""\n"
    "type 3 to Delete recording""\n")
choice = int(input("Enter your choice"))

########################retrive all call recordings##################

def retrive_call_recordings():
    global response_all_recording
    response_all_recording= requests.request("GET", "{}call-recording?account_id={}&created_date_min={}&created_date_max={}&type=voice_call&fields=voice_call_details".format(base_url,acc_id,created_date_min,created_date_max), headers=headers).json()
    return response_all_recording

if(choice==1,2,3):
    
    if(retrive_call_recordings()):
        print(response_all_recording["response"])

########################download call recordings####################

def download_call_recordings():
    global response_download_recording
    response_download_recording = requests.request("GET", "{}call-recording/{}?fields=file_access_details".format(base_url, call_recording_id), headers=headers).json()
    return response_download_recording
if(choice==2):    
    #user have to enter call_recording_id
    call_recording_id = input("copy and paste call_id from above list")
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
    del_rec_id = input("copy and paste call_id from above list which you want delete")       
    if(delete_recording()):
            print("Recording is successfully DELETED")

