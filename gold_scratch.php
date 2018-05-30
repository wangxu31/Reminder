<?php
	require 'Request.php';

	$url = 'https://stock.xueqiu.com/v5/stock/realtime/quotec.json?symbol=SZ159937';
	$headers = [
		'Accept: application/json, text/plain, */*',
		'Accept-Encoding: gzip, deflate, br',
		'Origin: https://xueqiu.com',
		'Referer: https://xueqiu.com/S/SZ159937',
		'Cookie: xq_a_token=7023b46a2c20d7b0530b4e9725f7f869c8d16e7d; xq_a_token.sig=ENETvzFNvxxbtpbc1TfjQpBjoaE; xq_r_token=19bf36bc92fc764fb5cc550744d7fe922069fd14; xq_r_token.sig=dRocG0wcTXQQLq8b3AmLY9RYqyk; _ga=GA1.2.1005415238.1527238277; u=811527238277788; device_id=66cc1f60db435bb5c4aa08fcedbe0128; _gid=GA1.2.640286935.1527662041; Hm_lvt_1db88642e346389874251b5a1eded6e3=1527662041,1527662367,1527662477,1527663028; Hm_lpvt_1db88642e346389874251b5a1eded6e3=1527663028; _gat_gtag_UA_16079156_4=1',
		'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36',
		'Accept-Language: zh-CN,zh;q=0.9,en;q=0.8,mt;q=0.7',
	];
	$request = new Request();
	$response = $request->get($url, $headers);
	$response = gzdecode($response);
	$data = json_decode($response, true);
	$data = $data['data'];
	$content = '';
	if (isset($data['market']) && isset($data['quote'])) {
		$high = $data['quote']['high'];
		$low = $data['quote']['low'];
		$percent = $data['quote']['percent'];
		$open = $data['quote']['open'];
		$lastClose = $data['quote']['last_close'];
		$current = $data['quote']['current'];
		$content = sprintf('今日开盘价%s，收盘价%s，最高%s，最低%s，涨跌幅%s，昨日收盘价%s。', $open, $current, $high, $low, $percent, $lastClose);
	} else {
		$data = array_shift($data);
		$current = $data['current'];
		$percent = $data['percent'];
		$chg = $data['chg'];
		$low = $data['low'];
		$high = $data['high'];
		$timestamp = $data['timestamp'];
		$timestamp = date('Y-m-d H:i:s', (int)($timestamp/1000));
		$content = sprintf('现价%s，涨跌价%s，涨跌幅%s，最高%s，最低%s', $current, $chg, $percent, $high, $low);
	}
	$content = assembleRequestData($content);
	$request->post($content);


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

	/**
	 * 'symbol' =>
	string(8) "SZ159937"
	'current' =>
	double(2.683)
	'percent' =>
	double(0.22)
	'chg' =>
	double(0.006)
	'timestamp' =>
	int(1527663843000)
	'volume' =>
	int(37120800)
	'amount' =>
	double(99654600)
	'market_capital' =>
	double(3767259283.07)
	'float_market_capital' =>
	NULL
	'turnover_rate' =>
	NULL
	'amplitude' =>
	double(0.22)
	'high' =>
	double(2.687)
	'low' =>
	double(2.681)
	'avg_price' =>
	double(2.685)
	'trade_volume' =>
	int(0)
	'side' =>
	int(1)
	'is_trade' =>
	bool(false)
	'level' =>
	int(1)
	'trade_session' =>
	NULL
	'trade_type' =>
	NULL
	 */

	/**
	 * {
	data: {
	market: {
	status_id: 7,
	region: "CN",
	status: "已收盘",
	time_zone: "Asia/Shanghai",
	},
	quote: {
	symbol: "SZ159937",
	code: "159937",
	exchange: "SZ",
	name: "博时黄金",
	type: 13,
	sub_type: "19",
	status: 1,
	current: 2.683,
	currency: "CNY",
	percent: 0.22,
	chg: 0.006,
	timestamp: 1527663843000,
	time: 1527663843000,
	lot_size: 100,
	tick_size: 0.001,
	open: 2.686,
	last_close: 2.677,
	high: 2.687,
	low: 2.681,
	avg_price: 2.685,
	volume: 37120800,
	amount: 99654600.1,
	turnover_rate: null,
	amplitude: 0.22,
	market_capital: 3767259283.07,
	float_market_capital: null,
	total_shares: 1404121984,
	float_shares: null,
	issue_date: 1409500800000,
	lock_set: null,
	},
	others: {
	pankou_ratio: -10.95
	},
	tags: [ ],
	},
	error_code: 0,
	error_description: "",
	}
	 */