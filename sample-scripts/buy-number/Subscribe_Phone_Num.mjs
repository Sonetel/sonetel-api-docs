/**
 * 
 * MIT License
 * 
 * Copyright (c) 2022 Sonetel AB
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * 
 */
import http from 'https';

/**
 * Sample script to purchase a phone number with Sonetel. Read more at docs.sonetel.com.
 * 
 * Here's how to use the function exported. Replace `'Access_Token'` with the Oauth access token.
 * 
 * Use the phone number inventory APIs to get an available phone number. Pass the phone number (as a string) as the 2nd argument.
 * 
 * `buyPhoneNumber('Access_Token','Num_To_Purchase');`
 * 
 * @param {string} accessToken The access token to authorize the request with Sonetel's API.
 * @param {string} numToPurchase The number to purchase in the E164 format (without the leading "+").
 */
export const buyPhoneNumber = (accessToken, numToPurchase) => {

  const host = process.env.SonetelAPIName ?? 'public-api.sonetel.com';

  // Check if the access token and the number to purchase were passed.
  // If not, throw an error.
  if (!accessToken){
    throw Error('accessToken must be defined')
  }
  if (!numToPurchase){
    throw Error('numToPurchase must be defined')
  }

  const parsedToken = JSON.parse(Buffer.from(accessToken.split('.')[1], 'base64').toString());
  const accountId = parsedToken.acc_id;

  const options = {
    hostname: host,
    path: `/account/${accountId}/phonenumbersubscription`,
    method: 'POST',
    headers: {
      "Content-Type": "application/json",
      "Authorization": `Bearer ${accessToken}`,
      "Accept": "application/json"
    }
  }
  const req = http.request(options, (res) => {
    const { statusCode } = res;
    if (statusCode !== 200){
      throw new Error(`Request failed. Status code ${statusCode}`);
    }
    res.setEncoding('utf-8');
    let rawData = '';
    res.on('data', (chunk) => rawData += chunk );
    res.on('end',() => {
      try {
        const parsedData = JSON.parse(rawData);
        console.log(parsedData);
      } catch (e) {
        console.error(e.message);
      }
    })
  });

  req.on('error', (e) => {
    throw new Error(`Request failed. ${e.message}`);
  });

  req.write(JSON.stringify({ phnum: numToPurchase }));
  req.end();

}
