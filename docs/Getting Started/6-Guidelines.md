# Guidelines

To keep the API consistent, there are certain general format definitions and principles followed. These are listed here.

## ISO Standards
- Countries are specified using the standard ISO codes as per [ISO 3166-1](https://en.wikipedia.org/wiki/ISO_3166-1 "ISO 3166-1").
- Currencies are specified as per the standard [ISO 4217](https://www.iso.org/iso-4217-currency-codes.html "ISO 4217").
- All time values used in filters and properties are specified in GMT only. The time format is used as per [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601 "ISO 8601"). The basic time format is the default format i.e. ```YYYYMMDDThh:mm:ssZ```.

## Phone numbers
- Phone numbers are specified in the [E164 notation](https://en.wikipedia.org/wiki/E.164 "Wikipedia"). For example, the number `+1 (202) 555 1234` will be represented as `12025551234`.
- Telephone country code is used as a property and filter in many resources, usually specified as the field `country_code`.
  - This field refers to the standard code assigned to a specific country(ies) by ITU-T.
  - For e.g. +1 for USA, or +46 for Sweden or +91 for India.
  - See [this wikipedia article](http://en.wikipedia.org/wiki/List_of_country_calling_codes) for more details.
  - Some countries may share the same telephone country code. For example, USA and Canada.
- Telephone area code is a 2 to 5 digit routing code for a geographical region in a country and forms part of an E164 number.
  - It is used as a property and filter (specified as area_code in the API) in some resources in the API.
  - Telephone area codes are often quoted with a national dialing prefix “0” or other digits, for e.g. a number in London listed as 020 8765 4321). The API expects the area code usage as the exact area code for that region without the prefix.
  - In some countries such as Italy, a “0” is a part of the area code, in which case “0” must be included in the area code when specified.


## Parameters

- **Multi-value filters**: Filters are used while performing searches. The filters are normally passed as query string parameters in the url.
  - In certain cases, it is required that multiple values can be passed for a single filter field.
  - For example let's assume that a search requires getting all resources that have the  property ```area_code``` with values either 747 or 515. In such cases, the values can be passed in the URL as a comma separated list i.e. ```https://api.sonetel.com/numberstocksummary/USA?area_code=747,515```
- **String wild-card search**: Some filters support wild-card search.
  - This is normally common for string based searches such as account names, user names, country name etc.
  - The wild card character used is asterisk `*` and a minimum of 3 characters are required to be provided for the search, unless explicitly specified in the filter definition.
  - For e.g. to search for all countries whose names start with “united”, use `name=united*` i.e. `https://api.sonetel.com/country?name=united*`
  - Wild card searches are case-insensitive.
- Boolean or flags in query strings in the urls always use “yes” or “no”, unless explicitly specified. For e.g. `parameter=yes`.
- Unknown parameters in urls are ignored while processing requests.

## General Guidelines

- All properties and string values follow an all lower case approach except where specifically mentioned. One exception to this rule are places where ISO codes such as those for country, currency, date/time etc. are specified.
- User passwords are between 5 and 32 characters and can carry anything except some special characters, namely (%^()+=\[]\\';/{}|/"<>?).
- Decimal values are represented with a dot (“.”). For e.g. 2.14 or 3.556 etc. As a general rule, the decimal precision (digits after decimal) are specified in the definition of the property or filter. Values passed are always rounded to the specified definition. For e.g. if a property credit_balance can take up to 2 decimals and a value of 5.334 is specified, the API automatically assumes the value to be 5.33.

