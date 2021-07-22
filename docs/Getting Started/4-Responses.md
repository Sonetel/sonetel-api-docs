---
tags: [technical, response, api response, api, api specs, api docs, documentation, api documentation]
---

# Responses

A request processed by the API would return either a HTTP 200 OK or a HTTP error code.

In the case a 200 OK is returned, the status of the request is specified in the content of the response.

For example, if the request is processed successfully, the following JSON response is returned.


```json
{ 
  "resource": resource_name,
  "status": "success",
  "response":
    {
      ...
    }
}
```

> **Important**: A 200 OK response doesn't imply that the API request was successful. Check the ```status``` field for the status of the API response. See [Error Codes](5-Error-Codes.md) for more information.

## HTTP Response Codes

** \*Add information about HTTP response codes here\* **

HTTP Response | Request Status | Description |
--------------|----------------|-------------
200 | Success | Returned when the API has processed the request and is returning a JSON response.
4xx | ? | ?
5xx | ? | ?|


## Partial response

In most cases, you do not need all the data in a resource, rather only specific parts of data in the resource.

Partial response enables just that. It allows you to specify the list of fields that are required in the response.

This saves bandwidth, which may be important when the API is consumed by mobile apps, and also enables the API to be customized to your needs.

You can specify what fields you need in a comma separated list under the filter “fields". The following format applies ```/resource?fields=field1,field2,field3```.



