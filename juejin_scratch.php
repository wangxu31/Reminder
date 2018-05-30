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
	$result = [
		'feedCard' => [
			'links' => $result
		],
		'msgtype' => 'feedCard'
	];
	$request->post($result);


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
	  if (count($result) > 6) {
		$result = array_slice($result, 0, 5);
	  }
	  return $result;
	}



