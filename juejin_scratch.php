<?php

	require 'Request.php';
	$request = new Request();
	$rand = rand(90221529103412, 346660025543840)/10000000000000000;
	$url = 'https://timeline-merger-ms.juejin.im/v1/get_entry_by_rank?src=web&before='.$rand.'&limit=20&category=5562b422e4b00c57d9b94b53';
	$sourceData = $request->get($url);
	$result = getRecords($sourceData);

	if (empty($result)) {
	  return;
	}
var_dump($result);
//	send($result);


	function send($data)
	{
	  // get cURL resource
	  $ch = curl_init();

	  // set url
	  curl_setopt($ch, CURLOPT_URL, 'https://oapi.dingtalk.com/robot/send?access_token=690807c35e5345db0560903e6472d969a3cabc18416174795e4d3007d29210f3');

	  // set method
	  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

	  // return the transfer as a string
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	  // set headers
	  curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'Content-Type: application/json; charset=utf-8',
	  ]);

	  // json body
	  $json_array = [
		'feedCard' => [
		  'links' => $data
		],
		'msgtype' => 'feedCard'
	  ];
	  $body = json_encode($json_array);

	  // set body
	  curl_setopt($ch, CURLOPT_POST, 1);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

	  // send the request and save response to $response
	  $response = curl_exec($ch);

	  // stop if fails
	  if (!$response) {
		die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
	  }

	  // echo 'HTTP Status Code: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . PHP_EOL;
	  // echo 'Response Body: ' . $response . PHP_EOL;

	  // close curl resource to free up system resources
	  curl_close($ch);
	}

	function getRecords($sourceData)
	{
	  try {
		$data = json_decode($sourceData, true);
	  } catch (Exception $e) {
		return [];
	  }
	  $records = $data['d']['entrylist'];
	  $result = [];
	  foreach ($records as $key => $record) {
		if ($key == 0) {
		  $picUrl = 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527159121473&di=4f64647d10fd318e18bbda2266aad9a7&imgtype=jpg&src=http%3A%2F%2Fimg3.imgtn.bdimg.com%2Fit%2Fu%3D1773043338%2C1669680535%26fm%3D214%26gp%3D0.jpg';
		} else {
		  $picUrl = 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1527159050998&di=35dbc8178bb72dee118bd13d5617b142&imgtype=0&src=http%3A%2F%2Feasyread.ph.126.net%2FtResVpXf-P5WvhAI7nGvBg%3D%3D%2F7916742105221565185.jpg';
		}
		$originalUrl = $record['originalUrl'];
		$title = $record['title'];
		$summaryInfo = $record['summaryInfo'];
		$element = [
			'title' => $title,
			'messageURL' => $originalUrl,
			'picURL' => $picUrl
		  ];
		array_push($result, $element);
	  }
	  if (count($result) > 10) {
		$result = array_slice($result, 0, 9);
	  }
	  return $result;
	}



