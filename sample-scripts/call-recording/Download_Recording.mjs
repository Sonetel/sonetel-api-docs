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
import http from "https";

/**
* Download a call recording audio file. Read more at [docs.sonetel.com](https://docs.sonetel.com).
* 
* Expects the arguments to be passed as an object, with the following keys:
* 1. accessToken
* 2. recordingId
*
* ## How to use:
* 
* ```javascript
* download_recording({
*   accessToken: "your_secret_access_token",
*   recordingId: "REdrm3ucw7zxjq"
* });
* ```
* 
*
* @param {String} accessToken This is the Oauth access token generated using the /oauth/token endpoint.
* @param {String} recordingId The unique ID of the recording to be downloaded
*
*/
const download_recording = ( { accessToken, recordingId } ) => {
  const host = process.env.SonetelAPIName ?? "public-api.sonetel.com";

  let url = `/call-recording/`;
  if (!accessToken || !accessToken instanceof String) {
    throw Error("accessToken is required and must be passed as a String");
  }

  if (!recordingId || !recordingId instanceof String) {
    throw Error("recordingId is required and must be passed as a String");
  }

  url += `${recordingId}?fields=file_access_details`;

  const options = {
    hostname: host,
    path: url,
    method: "GET",
    headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${accessToken}`,
        Accept: "application/json",
    },
  };

  const req = http.request(options, (res) => {
      const { statusCode } = res;

      if (statusCode !== 200) {
          throw new Error(`Request failed. Status code ${statusCode}`);
      }
      res.setEncoding("utf-8");
      let rawData = "";
      res.on("data", (chunk) => (rawData += chunk));
      res.on("end", () => {
          try {
              const parsedData = JSON.parse(rawData);
              // Change the line below to get a different output.
              console.log(parsedData.response.file.file_access_details);
          } catch (e) {
              console.error(e.message);
          }
      });
  });

  req.on("error", (e) => {
      throw new Error(`Request failed. ${e.message}`);
  });

  req.end();
}

export { download_recording };
