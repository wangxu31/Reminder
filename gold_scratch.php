<?php
	require 'Request.php';

	date_default_timezone_set("Asia/Shanghai");
	const CODE_MAP = ['SZ159937' => '博时黄金'];

//	$realTimeUrl = 'https://stock.xueqiu.com/v5/stock/realtime/quotec.json?symbol=SZ159937';
	$url = 'https://stock.xueqiu.com/v5/stock/quote.json?symbol=SZ159937';
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
//	if (date('H:i:s') >= '09:00:00' && date('H:i:s') <= '15:00:00') {
//		$response = $request->get($realTimeUrl, $headers);
//	} else {
		$response = $request->get($url, $headers);
//	}

	$response = gzdecode($response);
	$data = json_decode($response, true);
	$data = $data['data'];
	$content = '';
	if (isset($data['market']) && isset($data['quote'])) {
		$status = $data['market']['status'];
		$name = $data['quote']['name'];
		$high = $data['quote']['high'];
		$low = $data['quote']['low'];
		$percent = $data['quote']['percent'];
		$open = $data['quote']['open'];
		$lastClose = $data['quote']['last_close'];
		$current = $data['quote']['current'];
		$content = sprintf("%s【%s】%s 开盘价%s，收盘价%s，最高%s，最低%s，涨跌幅%s%%，昨日收盘价%s。", date('Y-m-d'), $status, $name, $open, $current, $high, $low, $percent, $lastClose);
	} else {
		return;
		$data = array_shift($data);
		$current = $data['current'];
		$percent = $data['percent'];
		$chg = $data['chg'];
		$low = $data['low'];
		$high = $data['high'];
		$name = CODE_MAP[$data['symbol']];
		$timestamp = $data['timestamp'];
		$timestamp = date('Y-m-d H:i:s', (int)($timestamp/1000));
		$content = sprintf("%s 实时【%s】当前价%s，涨跌价%s，涨跌幅%s%%，最高%s，最低%s", date('Y-m-d'), $name, $current, $chg, $percent, $high, $low);
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
	 * 实时接口
	 * {
	data: [
	{
	symbol: "SZ159937",
	current: 2.684,
	percent: 0.04,
	chg: 0.001,
	timestamp: 1527730374000,
	volume: 23700,
	amount: 63666,
	market_capital: 3768663405.06,
	float_market_capital: null,
	turnover_rate: null,
	amplitude: 0.19,
	high: 2.689,
	low: 2.684,
	avg_price: 2.686,
	trade_volume: 200,
	side: 1,
	is_trade: true,
	level: 1,
	trade_session: null,
	trade_type: null,
	}
	],
	error_code: 0,
	error_description: null,
	}
	 */

	/**
	 * {
	data: {
	market: {
	status_id: 1,
	region: "CN",
	status: "未开盘",
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

	/**
	 * {
	data: {
	market: {
	status_id: 3,
	region: "CN",
	status: "集合竞价",
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
	current: 2.695,
	currency: "CNY",
	percent: 0.45,
	chg: 0.012,
	timestamp: 1527729438000,
	time: 1527729438000,
	lot_size: 100,
	tick_size: 0.001,
	open: null,
	last_close: 2.683,
	high: null,
	low: null,
	avg_price: 2.695,
	volume: 0,
	amount: 0,
	turnover_rate: null,
	amplitude: null,
	market_capital: 3784108746.88,
	float_market_capital: null,
	total_shares: 1404121984,
	float_shares: null,
	issue_date: 1409500800000,
	lock_set: null,
	},
	others: {
	pankou_ratio: -46.43
	},
	tags: [ ],
	},
	error_code: 0,
	error_description: "",
	}
	 */

	/**
	 * {
	data: {
	market: {
	status_id: 5,
	region: "CN",
	status: "交易中",
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
	current: 2.687,
	currency: "CNY",
	percent: 0.15,
	chg: 0.004,
	timestamp: 1527730212000,
	time: 1527730212000,
	lot_size: 100,
	tick_size: 0.001,
	open: 2.689,
	last_close: 2.683,
	high: 2.689,
	low: 2.687,
	avg_price: 2.688,
	volume: 8800,
	amount: 23655,
	turnover_rate: null,
	amplitude: 0.07,
	market_capital: 3772875771.01,
	float_market_capital: null,
	total_shares: 1404121984,
	float_shares: null,
	issue_date: 1409500800000,
	lock_set: null,
	},
	others: {
	pankou_ratio: -87.82
	},
	tags: [ ],
	},
	error_code: 0,
	error_description: "",
	}
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