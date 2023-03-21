import urllib
import json
import requests
import os


def voiceapp_options(digit, option):
    voiceapps = {
        1: [api_req, "play", "prompt", "for  playing a message"],
        2: [api_req, "call", "user", "for connecting to a user"],
        3: [api_req, "call", "csg", "for connecting to  a team"],
        4: [api_req, "call", "phnum", "for connecting to a Phone Number"],
        5: [api_req, "connect", "app"],
        6: [api_req, "call", "other"],
        7: [api_req, "disconnect"]
    }
    if option in voiceapps:
        if option == 7:
           print("Disconnecting Voiceapp...")
           return voiceapps[option][0](digit, voiceapps[option][1])
        elif option == 6:
             print("Connecting to SIP address...")
             return voiceapps[option][0](digit, voiceapps[option][1], voiceapps[option][2], option)
        elif option == 5:
             ids = input("Choose,\n1 - To select Main Menu\n2 - To select System Prompt\n3 - To select Voice Mail\nEnter your Choice: ")
             if 1 <= ids <=  3:
                 return voiceapps[option][0](digit, voiceapps[option][1], voiceapps[option][2], ids)
             else:
                  return "Invalid Choice"
        else:
            ids = raw_input("Provide the respective id %s: "%voiceapps[option][3])
            return voiceapps[option][0](digit, voiceapps[option][1], voiceapps[option][2], ids)
    else:
        return "Invalid Choice"


def digit_options(option):
    if 0 <= option <= 10:
        b = input("Choose for updating the voiceapp to,\n1 - Play a Message\n2 - Connect to a User\n3 - Connect to a Team\n4 - Connect to a Phone Number\n5 - Connect to a Voiceapp(like Main Menu, System Prompt or Voice Mail)\n6 - Connect to a SIP Address\n7 - Disconnect the Voiceapp\nEnter your Choice: ")
        if 1 <= b <= 7:
            return voiceapp_options(option,b)
        else:
            return "Invalid Choice"
    else:
        return "Invalid Choice"      


def api_req(digit, a, t=None, i=None):

    if digit == 10:
       d = "timeout"
    else:
        d = "digit_"+str(digit)

    user = os.environ.get('sonetelUserName')
    pswd = os.environ.get('sonetelPassword')
    acc_id = os.environ.get('sonetelAccountId')

    print(user, pswd)

    base_url = "https://api.sonetel.com"

    #--------------GET ACCESS TOKEN------------------

    url = base_url + "/SonetelAuth/oauth/token"
    print(url)

    payload = urllib.urlencode({
        'grant_type': 'password',
        'password': pswd,
        'refresh':'yes',
        'username': user
    })

    headers = {
        'Accept': 'application/json, text/plain',
        'Content-Type': 'application/x-www-form-urlencoded'
    }

    response = requests.request(
        "POST",
        url,
        auth=('sonetel-api', 'sonetel-api'),
        data=payload,
        headers=headers
    )

    response.raise_for_status()

    token = response.json()

    #--------------------GET APP ID----------------------

    at = "Bearer "+ str(token['access_token'])
    #print(at)


    uri1 = base_url + "/account/" + acc_id + "/voiceapp/"
    print(uri1)

    headers = {
        'Authorization': at
    }

    response = requests.request(
        "GET",
        uri1,
        headers = headers
    )

    response.raise_for_status()

    body = response.json()

    #----------------------UPDATE VOICEAPP----------------------

    #getting default voiceapp id from the API response
    app_id = None
    for res in body['response']:
        if res['app_type'] == "ivr" and res['shortcode'] == "*21":
            app_id = res['app_id']
            sip = res['sip_address']
            break

    print(app_id)

    #getting default voiceapp id and sip address for the choice 5 and 6(connect to a Voiceapp and connect to a SIP Address)
    if i == 1:
       i = app_id
    if i == 2:
       for res in body['response']:
          if res['name'] == 'System Prompt':
             i = res['app_id']
             break
    if i == 3:
       for res in body['response']:
          if res['name'] == 'Voice Mail':
             i = res['app_id']
             break
    if i == 6:
       i = sip

    print(i)
    
    uri = uri1 + app_id
    print(uri)

    payload = {
        "menu": {
            d: {
                "action": a,
                "to": t,
                "id": i
            }
        }
    }

    headers = {
        'Authorization': at,
        'Accept': 'application/json, text/plain',
        'Content-Type': 'application/json'
    }
    #print(uri)
    try:
        response = requests.put(
            uri,
            data=json.dumps(payload),
            headers=headers
        )

        response.raise_for_status()

        body = response.json()
        if body['status'] == 'failed':
           print("Error in the api({}) call, here is the body".format(uri))
           return body
        else:
             return body
    except requests.exceptions.RequestException as e:
        print("Error: {}".format(e))


c = int(input("Choose the digit of the Voiceapp you want to update (0-10): "))
print(digit_options(c))
