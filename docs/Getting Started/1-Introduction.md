---
tags: [getting started, api basics, overview, introduction, sonetel api, api key, sonetel api documentation, documentation, docs, api docs]
---

# Introduction

The Sonetel API is a powerful REST web-service that enables you to integrate your business processes with [Sonetel's SaaS Communication platform](https://developer.sonetel.com). It allows you to efficiently manage your account, users as well as phone number resources.

The current version of the API supports the following.

Function | Description |
|---------|---------|
| [Authentication](../../reference/1_authentication.yaml) | Generate tokens that your applications can use to access the resources in your account.
| [Phone numbers](../../reference/2_phone_numbers.yaml) | Manage your existing phone numbers or purchase new ones. |
| [Make calls](../../reference/3_make_calls.yaml) | Make callback calls using your Sonetel account.|
| [Recorded Calls](../../reference/4_recorded_calls.yaml) | Manage your call recordings |
| [Voice Response](../../reference/5_voice_apps.yaml) | Change the default voice apps such as IVR and voicemail or add new ones as needed. |
| [Account](../../reference/6_account.yaml) | View & manage your Sonetel account details |
| [Users](../../reference/7_users.yaml) | Manage your user accounts, change their call settings and so on.| 



To get started, you need to sign up for a [developer account](https://app.sonetel.com/register?tag=api-developer). Once you have successfully setup your account, follow the instructions here to 

## Authentication

Protected API resources such as `/account/{accountId}/users` require authentication.

Read more about [how to authenticate your API requests](../../reference/1_authentication.yaml).

## Environments

Sonetel offers separate sandbox and production environments to help you develop your application without affecting your production data.

These environments can be accessed at the following URI:
- Production - https://public-api.sonetel.com/

## How to find your account ID?

You will need your account ID in order to access most resources linked to your account such as phone numbers and users.

Issue a `GET` request to `https://public-api.sonetel.com/account/` with your [access token](../../reference/1_authentication.yaml/paths/~1oauth~1token/post). The response object contains the basic details about your account including the account_id.

```json
{
    "resource": "account",
    "status": "success",
    "response": {
        "account_id": "YOUR_ACCOUNT_ID",
        "area_code": "212",
        "name": "Acme AB",
        "status": "active",
        "status_desc": "",
        "currency": "SEK",
        "address": "Address line 1",
        "address2": "address line 2",
        "city": "Stockholm",
        "zipcode": "114 11",
        "country": "SWE",
        "credit_balance": "5.04",
        "timezone": "GMT +01:00",
        "language": "eng",
        "priceplan": "regular",
        "plan_details": "",
        "website": {
            "website_status": "verified"
        },
        "daily_usage_limit": "25",
        "vat_relevant": true,
        "user_count": 3,
        "language_id": 41,
        "timezone_details": {
            "zone_name": "(GMT +1:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
            "zone_id": 4
        },
        "account_verified": true,
        "current_country": "SWE",
        "phnum_count": 2,
        "companyNameUpdated": false,
        "account_type": "main_account",
        "account_category": "regular"
    }
}
```

If you receive an error response to the above request, use the following workaround to find your account ID
1. Sign in to your account from [app.sonetel.com](https://app.sonetel.com).
2. Go to **Company Settings** > **Customer Service** > **Website chat**.
3. Click on *Installation* and copy your account ID from the code given on the page in the field `data-account-id`.