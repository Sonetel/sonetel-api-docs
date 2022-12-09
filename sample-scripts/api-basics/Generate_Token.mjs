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
import FormData from 'form-data';

/**
 * Sample script to generate API access token for your Sonetel account.
 * 
 * Import the module into your script and call it as shown below.
 * 
 * ```javascript
 * import {createAccessToken} from 'Generate_Token.mjs';
 * const token = await createAccessToken("user@example.com", "your_password");
 * ```
 * 
 * Read more at https://docs.sonetel.com
 * 
 * @param {string} username The email address used to sign in at sonetel.com
 * @param {string} password Login password for the Sonetel account.
 */
export const createAccessToken = (username, password) => {
  return new Promise(resolve => {
    const host = 'api.sonetel.com';
    const authUri = '/SonetelAuth/beta/oauth/token';

    if (!username){
        throw Error('Username is reqired to generate access token.');
    }
    if (!password){
        throw Error('Password is reqired to generate access token.');
    }

    const requestBody = new FormData();
    requestBody.append('grant_type', 'password');
    requestBody.append('username', username);
    requestBody.append('password', password);
    requestBody.append('refresh', 'yes');

    const options = {
        hostname: host,
        path: authUri,
        auth: 'sonetel-api:sonetel-api',
        method: 'post',
        headers: requestBody.getHeaders()
      }
      const req = http.request(options, (res) => {
        const { statusCode } = res;
        if(statusCode >= 500){
          throw Error (`Request failed with server error. Status code ${statusCode}`)
        }

        res.setEncoding('utf-8');

        let rawData = '';
        res.on('data', (chunk) => rawData += chunk);
        res.on('end', async () => {
          
          try {

            let dataToReturn;
            if (res.headers['content-type'].match(/application\/json/i)) {
              dataToReturn = JSON.parse(rawData);
            } else {
              dataToReturn = rawData;
            }

            resolve(dataToReturn);

          } catch (e) {
            console.error(e.message);
          }

        });
      });
    
      req.on('error', (e) => {
        throw Error(`Request failed. ${e.message}`);
      });

      requestBody.pipe(req);
  });
}
