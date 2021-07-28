# Frequently Asked Questions

## API

### Where can I get the API credentials?
The API uses tokens generated using the OAuth2 framework to authenticate API requests.

Read more about [how to authenticate API requests](../../reference/1_authentication.yaml).

### What username do I need to generate a token?

The username is the email address you used at the time of signing up. It is the same email address you use to sign in at [app.sonetel.com](https://app.sonetel.com).

### How can I find my account ID?

Issue a `GET` request to `https://public-api.sonetel.com/account/` with your access token. The response object contains the basic details about your account including the account_id.

If you receive an error response to the above request, use the following workaround to find your account ID
1. Sign in to your account from app.sonetel.com
2. Go to **Company Settings** > **Customer Service** > **Website chat**.
3. Click on *Installation* and copy your account ID from the code given on the page in the field `data-account-id`.

### What is a bearer token?

The access token [issued by our API](../../reference/1_authentication.yaml) is a bearer token.

These are long cryptic strings that are required by client applications to access protected API resources.

The token has to be included in the header of the request. `Authorization: Bearer <token>`

> **Important**: It is critical that you follow the security best practices while retriving and storing the tokens to prevent any unauthorized access to your account.

You can read more about [bearer tokens here](https://www.oauth.com/oauth2-servers/differences-between-oauth-1-2/bearer-tokens/).


## Service

### What does it cost to use the API?

Signing up for a developer account is completely free. There are no charges for accessing the API.

You only pay for the services i.e. calls, phone numbers, etc that you use. The charges are deducted from your prepaid account that you can [refill](https://app.sonetel.com/account-settings/prepaid-account) using a credit card, PayPal, Google Pay or other supported methods.

Optional upgrades to Premium and Enterprise plans provide discounted pricing, faster access to support and much more. Read about out available plans [here](https://developer.sonetel.com/plans).

### How can I get support?

If you need help with our API, please contact [api.support@sonetel.com](mailto:api.support@sonetel.com).

Please look at our website for information on the [available support plans](https://developer.sonetel.com/plans).

### How does caller ID work?

When you make an outgoing call through Sonetel and your outgoing CLI settings are set to automatic, the person that is being called will see the caller ID based on the following rules:

1. A Sonetel number assigned to you with business package enabled or a number that is free with Premium.
2. If more than one phone number is assigned to you, then the number in the country of the person being called will be shown. If you do not have a phone number in the same country, we will use another phone number assigned to you.
3. If there are no eligible phone numbers assigned to you, then any eligible number assigned to your Sonetel account (connected to voicemail or IVR, etc.) will be used as the caller ID.
4. If you do not have any phone numbers that can be used as caller ID, then your verified mobile number will be used as the caller ID.