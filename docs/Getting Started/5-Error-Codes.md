---
tags: [error, error code, technical data, specs]
---

# Error Codes

In case the request processing fails, details of the error are
returned in the response.

```json
{
  "response":
  {
    "detail": "Error string",
    "code": "Error code"
  },
  "resource": "resource_name",
  "status": "failed"
}
```

> **Important**: If the API request fails, the API will return a 200 OK with the `status: "failed"` in the response body.

## API error codes

### Complete List

The complete list of error codes and the associated error strings can be accessed using `/globaldata/errors`

```c
curl --request GET "https://api.sonetel.com/globaldata/errors"
```

### Resource specific error codes
You can get error codes specific to a resource by using the resource filter.

For example, to get the error codes for the `/phonenumbersubscription` endpoint, use the following request

```c
curl --request GET "https://api.sonetel.com/globaldata/errors?resource=Phonenumbersubscription"
```


**Response**

```json
[
  {
    "method" : "Create",
    "resource":"Phonenumbersubscription",
    "code":"10060000",
    "description":"Generic exception caught at Phone Number Subscription controller",
    "response":"Create Phone number subscription failed - Internal server error"
  },
  {
    "method":"Create",
    "resource":"Phonenumbersubscription",
    "code":"10060002",
    "description":"AccountId passed is non-numeric",
    "response":"AccountId should be numeric"
  },
  {
    "method":"Create",
    "resource":"Phonenumbersubscription",
    "code":"10060003",
    "description":"AccountId passed is empty",
    "response":"AccountId should not be Empty"
  },
  ...
]
```