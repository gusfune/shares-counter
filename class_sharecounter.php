<?php
class ShareCounter{
	/*
	 * Just a class to manipulate share count easily
	 * Example of use:
	 * 	$count = new ShareCounter("http://bitw.in");
	 * 	echo $count->getAllSharesCount();
	 *
	 * Easy, not?
	 */
	private $url; // Saves the URL in a private attribute
	public function __construct($url=''){
		/* Initialize the ShareCounter*/
		$this->setURL($url);
	}
	private function getCurrentPageURL() {
		/* Get the Current Page URL, with port number too */
		$pageURL = 'http'; // All pages start with HTTP, huh?
		if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {
			$pageURL .= "s"; // If HTTPS, just append the "s" to the string
		}
		$pageURL .= "://" . $_SERVER["SERVER_NAME"]; // Append the server name, or the domain where the script is used
		if ($_SERVER["SERVER_PORT"] != "80") { // If the port is different from 80...
			$pageURL .= ":" . $_SERVER["SERVER_PORT"]; // We need to append the port to the domain too
		}
		$pageURL .= $_SERVER["REQUEST_URI"]; // In the final, we just append the PATH of the requested script..
		return $pageURL; // And return! :D
	}
	private function request($url, $method="GET", $data="", $headers=array()){
		/* Just a function to make requests easily */
		/* We can apply a cache here, it's really simple, now :P */
		$curl = curl_init(); // Enable CURL, as the request need to be 'POST'
		curl_setopt($curl, CURLOPT_URL, $url); //Set the URL API
		curl_setopt($curl, CURLOPT_POST, $method=="POST"); // Set the request method
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Defines the content of the POST request
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // We need to receive a response, huh?
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers); // Set the type of the data in the HTTP POST request
		$curl_results = curl_exec ($curl); // Executes the call and receive the response
		curl_close ($curl); // Just closes the handler
		return $curl_results;
	}
	public function setURL($url=""){
		/* Set the URL to use with the Share counter. If empty, detect the actual page URL */
		if(empty($url)){ // Is empty?
			$this->url = $this->getCurrentPageURL(); // If yes, we just get the current page URL
		}
		else{
			$this->url = $url; // If not, we set the new URL
		}
	}
	public function getURL(){
		/* Just return the URL used with this counter */
		return $this->url;
	}
	public function getFacebookSharesCount(){
		/* Just return the number of shares of the link in  Facebook */
		$url_fb = "https://api.facebook.com/restserver.php?method=links.getStats&urls=" . $this->url; // Set the URL to API
		$content_fb = $this->request($url_fb); // Just request the API, using CURL..
		$data_fb = simplexml_load_string($content_fb); // Loads the XML file
		if ( isset($data_fb->link_stat->share_count) ) { // The counter has encountered?
			return $data_fb->link_stat->share_count; // If yes, just increment the global counter
		}
		else{
			return 0; // If not, we just return zero.
		}
	}
	public function getGooglePlusSharesCount(){
		/* Just return the number of shares of the link in Google Plus */
		$url_gplus = "https://clients6.google.com/rpc"; // Set the URL of the API
		$content_gplus = $this->request(
			$url_gplus, // The URL of the API
			"POST", // The method we need to use
			'[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $this->url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]', // The data to use with the API
			array('Content-type: application/json') // The type of request we use in the request body
		);// Just request the API, with a POST request..
		$data_gplus = json_decode($content_gplus, true); // Decode the file
		if ( isset($data_gplus) && isset($data_gplus[0]['result']['metadata']['globalCounts']['count']) ) { // If the JSON is valid and if we have the count
			$result = intval($data_gplus[0]['result']['metadata']['globalCounts']['count']); // We convert it into a INT number
			return $result; // And increment the global counter
		}
		else{
			return 0; // If not, we return zero..
		}
	}
	public function getLinkedinSharesCount(){
		/* Just return the number of shares of the link in Linkedin */
		$url_ln = "http://www.linkedin.com/countserv/count/share?url=" . $this->url . "&format=json"; //If yes, just set the URL of the API
		$data_ln = json_decode(file_get_contents($url_ln)); // Call the API and get the JSON
		if ( isset($data_ln->count) ) { // If the counter as encountered
			return $data_ln->count; // Increment the global counter
		}
		else{
			return 0; // If not, we just return zero..
		}
	}
	public function getSharesCount($services){
		/* Just return the sum of number of shares in determinated services */
		$shares = 0;
		foreach($services as $service){
			$method_call = array($this, "get".preg_replace("/\s/","",ucwords($service))."SharesCount"); // We just make a call
			$method_result = call_user_func($method_call); // Just call the internal methods :~
			if($method_result === FALSE){// It's an error?
				throw new Exception("The service ".$service." is not supported"); // If yes, we just raise a new exception :~
			}
			else if(is_int($method_result)){
				$shares += $method_result;
			}
		}
		return $shares;
	}
	public function getAllSharesCount(){
		/* Returns the SUM of number of shares in ALL services supported by this script */
		return $this->getSharesCount(array("facebook","linkedin","google plus"));
	}
	public function echoAllSharesCount(){
		/* Echo the sum of shares count in ALL services */
		echo $this->getAllSharesCount();
	}
}
?>
