<?php
function sharesCounter($url = '', $facebook = true, $twitter = true, $gplus = true, $linkedin = true) {
	$shares = 0;

	if ( !isset($url) || $url === '' ) {
		if ( isset($_SERVER['https']) ) {
			$protocol = 'https://';
		} else {
			$protocol = 'http://';
		}
		$url =  $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	}

	if ( $facebook ) {
		$url_fb = "http://api.facebook.com/restserver.php?method=links.getStats&urls=" . $url;
		$data_fb = simplexml_load_file($url_fb);
		if ( isset($data_fb->link_stat->share_count) ) {
			$shares = $shares + $data_fb->link_stat->share_count;
		}
	}

	if ( $twitter ) {
		$url_tw = "http://urls.api.twitter.com/1/urls/count.json?url=" . $url;
		$data_tw = json_decode(file_get_contents($url_tw));
		if ( isset($data_tw->count) ) {
			$shares = $shares + $data_tw->count;
		}
	}

	if ( $gplus ) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		$curl_results = curl_exec ($curl);
		curl_close ($curl);
		$json = json_decode($curl_results, true);

		if ( isset($json) && isset($json[0]['result']['metadata']['globalCounts']['count']) ) {
			$result = intval($json[0]['result']['metadata']['globalCounts']['count']);
			$shares = $shares + $result;
		}
	}

	if ( $linkedin ) {
		$url_ln = "http://www.linkedin.com/countserv/count/share?url=" . $url . "&format=json";
		if ( isset($data_ln->count) ) {
			$shares = $shares + $data_ln->count;
		}
	}

	echo intval($shares);
}
?>