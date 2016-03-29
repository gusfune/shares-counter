<?php
function sharesCounter($url = '', $echo = true, $facebook = true, $twitter = true, $gplus = true, $linkedin = true, $vk = true) {
	/* Returns (or just echo) the number of shares of a specific URL in various social networks */
	$shares = 0; // Start with zero shares

	if ( !isset($url) || $url === '' ) { // It's empty or not set?
		if ( isset($_SERVER['https']) ) { // It's HTTP Secure?
			$protocol = 'https://'; // The Protocol is HTTPS://
		} else {
			$protocol = 'http://'; // Otherwise, it's just HTTP
		}
		$url =  $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; // Get the actual URL
	}

	if ( $facebook ) { // Facebook is active?
		$url_fb = "https://api.facebook.com/restserver.php?method=links.getStats&urls=" . $url; // Set the URL to API
		$data_fb = simplexml_load_file($url_fb); // Loads the XML file
		if ( isset($data_fb->link_stat->share_count) ) { // The counter has encountered?
			$shares += $data_fb->link_stat->share_count; // If yes, just increment the global counter
		}
	}

	if ( $twitter ) { // Twitter is active?
		$url_tw = "http://urls.api.twitter.com/1/urls/count.json?url=" . $url; // Set the URL to API
		$data_tw = json_decode(file_get_contents($url_tw)); // Get the URL and decode the JSON
		if ( isset($data_tw->count) ) { // The count of shares is set?
			$shares = $shares + $data_tw->count; // If yes, increment the counter
		}
	}

	if ( $gplus ) { // Google Plus is active?
		$curl = curl_init(); // Enable CURL, as the request need to be 'POST'
		curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc"); //Set the URL API
		curl_setopt($curl, CURLOPT_POST, 1); // Set the request as POST
		curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]'); // Defines the fields of the POST request
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // We need to receive a response, huh?
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Set the type of the data in the HTTP POST request
		$curl_results = curl_exec ($curl); // Executes the call and receive the response
		curl_close ($curl); // Just closes the handler
		$json = json_decode($curl_results, true); // Decode the file

		if ( isset($json) && isset($json[0]['result']['metadata']['globalCounts']['count']) ) { // If the JSON is valid and if we have the count
			$result = intval($json[0]['result']['metadata']['globalCounts']['count']); // We convert it into a INT number
			$shares += $result; // And increment the global counter
		}
	}

	if ( $linkedin ) { // Is Linkedin active?
		$url_ln = "http://www.linkedin.com/countserv/count/share?url=" . $url . "&format=json"; //If yes, just set the URL of the API
		$data_ln = json_decode(file_get_contents($url_ln)); // Call the API and get the JSON
		if ( isset($data_ln->count) ) { // If the counter as encountered
			$shares += $data_ln->count; // Increment the global counter
		}
	}

	if ( $vk ) { // VK is active?
		$url_vk = 'http://vk.com/share.php?act=count&index=1&url=' . $url;
		$data_vk = file_get_contents($url_vk);
		$data_vk = str_replace('VK.Share.count(1,', '', $data_vk);
		if (intval($data_vk)) {
			$shares += intval($data_vk);
		}
	}

	if ( $echo ) { // We have to print the value?
		echo intval($shares); // If yes, we echo the value!
	} else {
		return intval($shares); // If not..We just return
	}
}
?>
