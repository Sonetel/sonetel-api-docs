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
* Get details of the calls recorded in your account. Read more at [docs.sonetel.com](https://docs.sonetel.com).
* 
* If the recordingId is passed to the function, it returns the details of that recording. Otherwise, returns the list of all call recordings.
* 
* ## How to use:
*
* `get_recording({ accessToken: 'Your_Access_Token', fields: ['Optional_Fields'], recordingId: 'Recording_Id'});`
*
* ## Timestamp format
* 
* The timestamps should follow the ISO_8601 format i.e. YYYYMMDDTHH:mm:ssZ.
*
*
* @param {String} accessToken This is the Oauth access token generated using the /oauth/token endpoint.
* @param {Array} fields An array containing the additional fields required in the response. Allowed values `file_access_details`, `voice_call_details` & `file`.
* @param {String} startDate Limit the results to recordings created after this timestamp.
* @param {String} endDate Limit the results to recordings created before this timestamp
*
*/
const get_recordings = ({accessToken, fields, startDate, endDate}) => {

    const host = process.env.SonetelAPIName ?? "public-api.sonetel.com";

    let url = `/call-recording/`;
    let query = [];

    if (!accessToken || !accessToken instanceof String) {
        throw Error("accessToken is required and must be passed as a String");
    }

    const parsedToken = JSON.parse(
        Buffer.from(accessToken.split(".")[1], "base64").toString()
    );
    const accountId = parsedToken.acc_id;
    query.push(`account_id=${accountId}`);

    if (startDate && endDate) {
        // If the recording ID is specified, then fetch that particular recording.
        query.push(`created_date_min=${startDate}`);
        query.push(`created_date_max=${endDate}`);
    }

    if (fields && fields instanceof Array){
        query.push(`fields=${fields.join()}`);
    }

    // Final URL
    url += `?${query.join('&')}`;

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
                console.log(parsedData);
            } catch (e) {
                console.error(e.message);
            }
        });
    });

    req.on("error", (e) => {
        throw new Error(`Request failed. ${e.message}`);
    });

    req.end();
};

export { get_recordings };
