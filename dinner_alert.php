<?php
	require 'Request.php';

	/**
	 * @param string $content
	 * @param bool $isAtAll
	 * @return array
	 */
	function assembleRequestData(string $content, bool $isAtAll = false)
	{
		$data = [
			'msgtype' => 'text',
			'text' => [
				'content' => $content,
			],
			'at' => [
				'isAtAll' => $isAtAll,
				'atMobiles' => [
				]
			]
		];
		return $data;
	}

	$data = assembleRequestData('Dinner Time 饿晕了，准备吃晚饭啦！');
	$request = new Request();
	$response = $request->post($data);