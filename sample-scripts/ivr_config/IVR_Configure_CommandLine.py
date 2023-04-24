import urllib.parse
import json
import requests
import os


user = os.environ.get('sonetelUserName')
pwd = os.environ.get('sonetelPassword')
acc_id = os.environ.get('sonetelAccountId')
base_url = "https://public-api.sonetel.com"


class VoiceApp:

    def __init__(self, digit, option):
        self.digit = digit
        self.option = option


    def getAccessToken(self):
        '''This method doesnot accept any arguments but it uses global variables(like user, pwd, acc_id and base_url) to get a new access token every time this method is called. It returns the new access token by adding Bearer as the prefix'''
        url = base_url + "/SonetelAuth/oauth/token"

        print(user, pwd)
        print(url)

        payload = urllib.parse.urlencode({
            'grant_type' : 'password',
            'password' : pwd,
            'refresh' : 'yes',
            'username' : user
        })

        headers = {
            'Accept' : 'application/json, text/plain',
            'Content-Type' : 'application/x-www-form-urlencoded'
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

        return ("Bearer "+str(token['access_token']))


    def getVoiceAppConfig(self, token):
        '''This method accepts the access token as its argument and through this and the url, it fetches the voiceapp config by making a get request. It returns the url and the response of the api call'''
        uri = base_url + "/account/" + str(acc_id) + "/voiceapp/"
        print(uri)

        headers = {
            'Authorization' : token
        }

        response = requests.request(
            "GET",
            uri,
            headers = headers
        )

        response.raise_for_status()

        return uri, response.json()

    
    def updateConfig(self):
        '''This method does not accept any arguments, it compares the user option with the voiceapps dictionary and calls the updateVoiceApp method with the appropriate argument(s)'''
        voiceapps = {
            1: ["play", "prompt", "for playing a message"],
            2: ["call", "user", "for connecting to a user"],
            3: ["call", "csg", "for connecting to  a team"],
            4: ["call", "phnum", "for connecting to a Phone Number"],
            5: ["connect", "app"],
            6: ["call", "other"],
            7: ["disconnect"]
        }
        try:
            if self.option == 7:
                print("Disconnecting Voiceapp...")
                return self.updateVoiceApp(voiceapps[self.option][0])
            elif self.option == 6:
                print("Connecting to SIP address...")
                return self.updateVoiceApp(voiceapps[self.option][0], voiceapps[self.option][1], self.option)
            elif self.option == 5:
                ids = int(input("Choose,\n1 - To select Main Menu\n2 - To select System Prompt\n3 - To select Voice Mail\nEnter your Choice: "))
                if 1 <= ids <=  3:
                    return self.updateVoiceApp(voiceapps[self.option][0], voiceapps[self.option][1], ids)
                else:
                    return "Invalid Choice"
            else:
                ids = input("Provide the respective id %s:"%voiceapps[self.option][2])
                return self.updateVoiceApp(voiceapps[self.option][0], voiceapps[self.option][1], ids)
        except Exception as e:
            return e
        
        
    def updateVoiceApp(self, action, to=None, i=None):
        '''This method accepts 3 arguments in which 1 is mandatory and the other 2 are optional based on the voiceapp update choice. In this method we will call the getAccessToken method and pass it as an argument to the getAppID method call which returns the url and api response from which we will get the appropriate app id. Then we will construct the appropriate payload for put request api call which updates the default voiceapp'''
        if self.digit == 'timeout':
            d = self.digit
        else:
            d = 'digit_'+str(self.digit)

        t = self.getAccessToken()
        #print(t)
        url, body = self.getVoiceAppConfig(t)
        #print(body)

        for res in body['response']:
            if res['app_type'] == 'ivr' and res['shortcode'] == '*21':
                app_id = res['app_id']
                sip = res['sip_address']
                break

        print(app_id)

        if i == 1:
            i = app_id
        elif i == 2:
            for res in body['response']:
                if res['name'] == 'System Prompt':
                    i = res['app_id']
                    break
        elif i == 3:
            for res in body['response']:
                if res['name'] == 'Voice Mail':
                    i = res['app_id']
                    break
        elif i == 6:
            i = sip

        print(i)

        uri = url + app_id
        print(uri)

        payload = {
            "menu" : {
                d : {
                    "action": action,
                    "to" : to,
                    "id" : i
                }
            }
        }

        headers = {
            'Authorization': t,
            'Accept': 'application/json, text/plain',
            'Content-Type': 'application/json'
        }

        try:
            response = requests.put(
                uri,
                data=json.dumps(payload),
                headers=headers
            )

            response.raise_for_status()

            res = response.json()
            if body['status'] == 'failed':
                print("Error in the api({}) call, here is the body".format(uri))
                return res
            else:
                return res
        except requests.exceptions.RequestException as e:
            print("Error: {}".format(e))



if __name__ == "__main__":
     try:
          i = input("Choose the digit of the Voiceapp you want to update (0-9 or timeout): ")
          if i == 'timeout' or 0 <= int(i) <= 9:
               b = int(input("Choose for updating the voiceapp to,\n1 - Play a Message\n2 - Connect to a User\n3 - Connect to a Team\n4 - Connect to a Phone Number\n5 - Connect to a Voiceapp(like Main Menu, System Prompt or Voice Mail)\n6 - Connect to a SIP Address\n7 - Disconnect the Voiceapp\nEnter your Choice: "))
               if 1 <= b <= 7:
                    h = VoiceApp(i, b)
                    print(h.updateConfig())
               else:
                    print("Invalid Choice")
          else:
               print("Invalid Choice")
     except Exception:
          print("Invalid Choice")
