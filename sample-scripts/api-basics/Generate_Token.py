"""
Use your Sonetel username and password to generate an API token. The token can then be used as a bearer token in other API calls to buy a number, make calls, change the call forwarding settings and so on.

This example uses the Content-Type 'application/x-www-form-urlencoded' to demonstrate that your application can use either 'multipart/form-data' or 'application/x-www-form-urlencoded' when requesting for an access token.

API Documentation: https://docs.sonetel.com/docs/sonetel-documentation/YXBpOjExMzI3NDM3-authentication

Sonetel Developer Home: https://sonetel.com/en/developer/

"""
import urllib.parse
import requests
import os

# For security reasons, we recommend that you DO NOT hard code your credentials into scripts.
# Here we have assumed that your username and password are stored in your operating system's environment variables.
user = os.environ.get('sonetelUserName')
pswd = os.environ.get('sonetelPassword')

url = "https://api.sonetel.com/SonetelAuth/oauth/token"

# Prepare the request body
# If you do not want a refresh_token, remove the line 'refresh':'yes' from the request.
payload = urllib.parse.urlencode({ 
    'grant_type' : 'password',
    'password' : pswd,
    'refresh':'yes',
    'username' :  user
    })

# Request headers
headers = {
  'Accept': 'application/json, text/plain',
  'Content-Type': 'application/x-www-form-urlencoded'
}

try:
    """
    When generating the token, your Sonetel email address and password are sent in the request body. However, you also need to supply HTTP BASIC authentication with the request using sonetel-api as the username as well as the password.
    """
    response = requests.request(
        "POST", 
        url,
        auth=('sonetel-api', 'sonetel-api'), 
        data=payload, 
        headers=headers
        )
    
    # Raise an error if the API request failed.
    response.raise_for_status()
    # Print the result if the API request was successful.
    token = response.json()
    
    # Use the token, to get basic information about the Sonetel account.
    accountInfo = requests.get(
        url = 'https://public-api.sonetel.com/account/',
        headers = { "Authorization" : f'Bearer {token["access_token"]}' }
        )
    accountInfo.raise_for_status()
    print(accountInfo.json())

except requests.exceptions.RequestException as e:
    raise SystemExit(e)
