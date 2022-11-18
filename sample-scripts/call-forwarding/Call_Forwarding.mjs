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
 * Change the call forwarding of your phone number using Sonetel's API. Read more at [docs.sonetel.com](https://docs.sonetel.com).
 *
 * ## How to use:
 *
 * `change_call_forwarding('Access_Token','Phone_Number',settings);`
 *
 * The `settings` Object contains information about the entity where the calls to the phone number must be forwared. It contains the following:
 * - `connect_to`: the phone number, SIP address or ID of the voice app where the incoming calls should be forwarded. Phone numbers should preferably be in the +NUMBER international format.
 * - `connect_to_type`: The type of the entity specified in `connect_to`.
 *
 * ### Example - forward calls to a phone number
 * ```json
 * {
 *     connect_to: "+12025551234",
 *     connect_to_type: "phnum"
 * }
 * ```
 *
 * @param {String} accessToken This is the Oauth access token generated using the /oauth/token endpoint.
 * @param {String} e164Number This is the phone number for which the call forwarding has to be changed. This must be a phone number you have subscribed.
 * @param {Object} settings The settings object contains the details of where the calls must be forwarded.
 *
 */
const change_call_forwarding = (accessToken, e164Number, settings) => {
	const host = process.env.SonetelAPIName ?? "public-api.sonetel.com";

	if (!accessToken || !accessToken instanceof String) {
		throw Error("accessToken is required and must be passed as a String");
	}

	if (!e164Number || !e164Number instanceof String) {
		throw Error("e164Number is required and must be passed as a String");
	}

	if (
		!settings ||
		!settings.hasOwnProperty("connect_to") ||
		!settings.hasOwnProperty("connect_to_type")
	) {
		throw Error(
			'settings is required and must be passed as an Object with "connect_to" and "connect_to_type" properties.'
		);
	}

	const parsedToken = JSON.parse(
		Buffer.from(accessToken.split(".")[1], "base64").toString()
	);

	const accountId = parsedToken.acc_id;

	const options = {
		hostname: host,
		path: `/account/${accountId}/phonenumbersubscription/${e164Number}`,
		method: "PUT",
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
	req.write(JSON.stringify(settings));

	req.end();
};

export { change_call_forwarding };
