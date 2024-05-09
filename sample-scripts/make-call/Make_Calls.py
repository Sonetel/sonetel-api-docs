"""
Sample script to initiate a callback call using Sonetel's Callback API.

The callback API, expects a POST request with a JSON body that contains the details of the numbers to call. An access token, generated using your Sonetel username and password, is used to authenticate the request. The token is passed as a Bearer token in the request's header.

API Documentation: https://docs.sonetel.com/docs/sonetel-documentation/YXBpOjE1OTMzOTIy-make-calls
Price list: https://sonetel.com/callprices

Sonetel Developer Home: https://sonetel.com/en/developer/
"""

import requests
import json

# API Base URL
base_url = "https://public-api.sonetel.com/"


# Phone numbers that the callback API will use.
#
# phone_1 should be your phone number i.e the number of the person making the call.
# phone_2 should be the number of the person being called.
# 
# It is recommended that both the numbers be entered in the international +NUMBER format.
phone_1 = "+NUMBER1"
phone_2 = "+NUMBER2"

# Generate an API access token and use it to verify your identity.
# 
# How to generate an access token?
# https://docs.sonetel.com/docs/sonetel-documentation/YXBpOjExMzI3NDM3-authentication
access_token = "ENTER_ACCESS_TOKEN"



# Create the Headers to be used in the POST request.
headers = {"Authorization": "Bearer {}".format(access_token),
            'Content-Type': 'application/json;charset=UTF-8'
            }


# The payload contains the body that will be sent with the request.
# Here is a brief explanation of the fields:
# 
# -> app_id: a string to uniquely identify the app that is making the request. You can replace the default string with your own value such as "MyCallbackApp_1.2.3 if needed."
# -> call1: the phone number of the first person that will receive the call. This should be your phone number.
# -> call2: the number of the second person that will receive the call. This should be the number of the person you wish to speak to. 
# -> show_1 & show_2: the caller ID to be shown in the first and second leg of the call. Only a number that you are allowed to display as the caller ID can be used. It is recommended to use default 'automatic' setting.

payload =json.dumps({
  "app_id": 'SonetelCallbackApp_Python',
  "call1": phone_1,
  "call2": phone_2,
  "show_1": "automatic",
  "show_2": "automatic"
})

# Function to initiate callback.
def make_call(url: str, headers: dict, payload: str) -> None:
    """
    Initiate the callback request using Sonetel's callback API.

    Parameters
    1. url - the Base API URI.
    2. headers - the headers to be sent with the API request.
    3. payload - the request body.

    Return: None
    
    This function does not return any value. The success and failure messages are printed to the console.
    """
    try:
      response = requests.request(
        "POST",
        "{}make-calls/call/call-back".format(url),
        headers=headers,
        data=payload
        ).json()

      response.raise_for_status()
      print(response)
    except requests.exceptions.RequestException as e:
      raise SystemExit(e)

# Start the call.
make_call(url=base_url, headers=headers, payload=payload)
