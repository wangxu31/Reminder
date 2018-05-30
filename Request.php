<?php
	/**
	 * Created by PhpStorm.
	 * User: clive
	 * Date: 2018/5/30
	 * Time: 上午11:54
	 */

class Request
{
	const ACCESS_TOKEN = '690807c35e5345db0560903e6472d969a3cabc18416174795e4d3007d29210f3';
	const BASE_URL = "https://oapi.dingtalk.com/robot/send?access_token=";

	public function post($data, $url = '', $headers = [])
	{
		if (!$url) {
			$url = self::BASE_URL.self::ACCESS_TOKEN;
		}

		// get cURL resource
		$ch = curl_init();

		// set url
		curl_setopt($ch, CURLOPT_URL, $url);

		// set method
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

		// return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// set headers
		if (!$headers) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json; charset=utf-8',
			]);
			$data = json_encode($data);
		} else {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		// set body
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		// send the request and save response to $response
		$response = curl_exec($ch);

		// stop if fails
		if (!$response) {
			die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
		}

//		echo 'HTTP Status Code: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . PHP_EOL;
//		echo 'Response Body: ' . $response . PHP_EOL;		// close curl resource to free up system resources

		curl_close($ch);
		return $response;
	}

	function get($url, $headers = [])
	{
		// get cURL resource
		// get cURL resource
		$ch = curl_init();

		// set url
		curl_setopt($ch, CURLOPT_URL, $url);

		// set method
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

		// return the transfer as a string
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		// set headers
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		// send the request and save response to $response
		$response = curl_exec($ch);

		// stop if fails
		if (!$response) {
			die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
		}

//		echo 'HTTP Status Code: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . PHP_EOL;
//		echo 'Response Body: ' . $response . PHP_EOL;

		// close curl resource to free up system resources
		curl_close($ch);
		return $response;
	}
}