<?php
// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0

Flight::register('call', 'Call');
class Call{

	/**
	* Formats an array of key=>value cookies into a string formatted for an http request.
	* @param	array $cookie An array of key value pairs.
	* @return	string
	*/
	public function format_cookies($cookie){

		$return = '';
		foreach($cookie as $key => $value){

			$return .= $key.'='.$value.'; ';

		}

		return $return;

	}

	/**
	* Formats an array of key=>value headers into an array of strings formatted for an http request.
	* @param	array $cookie An array of key value pairs.
	* @return	array
	*/
	public function format_headers($headers){

		$return = array();
		foreach($headers as $key => $value){

			$return[] = $key.': '.$value;

		}

		return $return;

	}

	/**
	* Returns a user agent from a list of known user agents
	* @param	string $user_agent Optional. If 'random', returns a random one. If empty, returns chrome.
	* @return	string
	*/
	public function get_user_agent($user_agent){

		if($user_agent == 'random'){

			$options = array(
				'ig',
				'facebook',
				'iphone',
				'ipad',
				'fbiphone',
				'opera',
				'edge',
				'edgemobile',
				'snapios',
				'snapandroid',
				'chromeiphone',
				'blink',
				'chrome',
			);

			$user_agent = $options[array_rand($options)];

		}

		switch($user_agent){
			case Flight::get('string_site_name'):
				$return = Flight::get('string_site_name').'/1.0';
			break;
			case 'ig': // found here https://developers.whatismybrowser.com/useragents/parse/63586228instagram-ios-iphone-11-webkit
				$return = 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 Instagram 169.0.0.21.133 (iPhone12,1; iOS 14_2; en_US; en-US; scale=2.00; 828x1792; 261791898)';
			break;
			case 'facebookbot':
				$return = 'facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)';
			break;
			case 'facebook':
				$return = 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_1_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 [FBAN/FBIOS;FBDV/iPhone9,1;FBMD/iPhone;FBSN/iOS;FBSV/13.1.2;FBSS/2;FBID/phone;FBLC/ar_AR;FBOP/5]';
			break;
			case 'googleimagebot':
				$return = 'Googlebot-Image/1.0';
			break;
			case 'googlebotmobile':
				$return = 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/W.X.Y.Z‡ Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
			break;
			case 'googlebot':
				$return = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
			break;
			case 'iphone':
				$return = 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1';
			break;
			case 'ipad':
			$return = 'Mozilla/5.0 (iPad; CPU OS 14_4_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1';
			break;
			case 'bing':
				$return = 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)';
			break;
			case 'yahoo':
				$return = 'Mozilla/5.0 (compatible; Yahoo! Slurp; http://help.yahoo.com/help/us/ysearch/slurp)';
			break;
			case 'fbiphone':
				$return = 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0_1 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Mobile/10A523 [FBAN/FBIOS;FBAV/5.3;FBBV/89182;FBDV/iPhone4,1;FBMD/iPhone;FBSN/iPhoneOS; FBSV/6.0.1;FBSS/2; FBCR/O2;FBID/phone;FBLC/en_US]';
			break;
			case 'tor':
				$return = 'Mozilla/5.0 (Windows NT 6.1; rv:45.0) Gecko/20100101 Firefox/45.0';
			break;
			case 'opera':
				$return = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Safari/537.36 OPR/86.0.4363.59';
			break;
			case 'edge':
				$return = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36 Edg/90.0.818.41';
			break;
			case 'edgemobile':
				$return = 'Mozilla/5.0 (Windows Mobile 10; Android 10.0; Microsoft; Lumia 950XL) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Mobile Safari/537.36 Edge/40.15254.603';
			break;
			case 'snapios':
				$return = 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Snapchat/11.1.6.0 (like Safari/604.1)';
			break;
			case 'snapandroid':
				$return = 'Mozilla/5.0 (Linux; Android 11; SM-G970U Build/RP1A.200720.012; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/88.0.4324.93 Mobile Safari/537.36Snapchat11.4.5.67 Beta (SM-G970U; Android 11#G970USQS4FUA1#30; gzip)';
			break;
			case 'chromeiphone':
				$return = 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/87.0.4280.163 Mobile/15E148 Safari/604.1';
			break;
			case 'blink':
				$return = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36';
			break;
			case 'brave':
				$return = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.81 Safari/537.36';
			break;
			case 'chrome':
			default:
				$return = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36 OPR/63.0.3368.35';
			break;
		}

		return $return;

	}

	/**
	* There's an option to return headers with api->call(), this formats them
	* @link		https://blog.devgenius.io/how-to-get-the-response-headers-with-curl-in-php-2173b10d4fc5
	* @param	string $headers_string The header data parsed out of the response.
	* @return	array Of headers.
	*/
	function parse_headers($headers_string){

		$headers = array();
		$headersTmpArray = explode('\r\n', $headers_string);

		for($i=0 ; $i < count($headersTmpArray); ++$i){

			// Skip two \r\n lines at the end of the headers
			if(strlen($headersTmpArray[$i]) > 0 ){

				// Filter out HTTP status codes
				if(strpos($headersTmpArray[$i] , ':')){
					$headerName = substr( $headersTmpArray[$i] , 0 , strpos($headersTmpArray[$i], ':'));
					$headerValue = substr( $headersTmpArray[$i] , strpos($headersTmpArray[$i], ':') + 1);
					$headers[$headerName] = $headerValue;
				}

			}
		}

		return $headers;

	}

	/**
	* Call an API endpoint using CURL
	* @param	string $endpoint The fully pathed URL of the API we're calling.
	* @param	array $data Optional. Key=>value pairs to be POST'd or added as Query parameters.
	* @param	string $method Optional. GET|POST
	* @param	array $headers Optional. Key=>value pairs to be included as headers in the request.
	* @param	array $cookies Optional. Key=>value pairs to be included as cookies in the request.
	* @param	bool $proxy Optional. Whether or not to make the request using a proxy.
	* @return	string The response, typically json.
	*/
	public function api($endpoint, $data = array(), $method = 'GET', $headers = array(), $cookies = array(), $proxy = false, $include_headers = false){

		// Set non proxy defaults
		$ch = curl_init();
		$options = array(
				CURLOPT_FRESH_CONNECT  => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_VERBOSE		   => false,
				CURLOPT_HEADER         => false,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_ENCODING       => "",
				CURLOPT_AUTOREFERER    => true,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_HEADER		   => $include_headers,
				CURLOPT_CONNECTTIMEOUT => 20,
				CURLOPT_TIMEOUT        => 20,
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_COOKIE		   => $this->format_cookies($cookies),
				CURLOPT_HTTPHEADER	   => $this->format_headers($headers),
				CURLOPT_USERAGENT      => $this->get_user_agent(Flight::get('string_site_name'))
		);

		// Format data
		if($method == 'GET'){
			$data = http_build_query($data);
			$options[CURLOPT_URL] = $endpoint.'?'.$data;
		}else{
			$options[CURLOPT_URL] = $endpoint;
			$options[CURLOPT_POST] = true;
			if(is_array($data)){
				$options[CURLOPT_POSTFIELDS] = http_build_query($data);
			}else{
				$options[CURLOPT_POSTFIELDS] = $data;
			}

		}

		// Make the request
		if(!$proxy){

			curl_setopt_array($ch, $options);
			$body = curl_exec($ch);
			$http_info = curl_getinfo($ch);
			$ch_error = curl_error($ch);

			// Log each request
			Flight::core()->log('requests', 'No Proxy', $options[CURLOPT_URL].' - '.$http_info['http_code'].' - '.$ch_error.'-'.$body);

		}else{

			// Try twice each with a different IP
			for($attempts = 1; $attempts < 3; $attempts++){

				$options[CURLOPT_PROXY] = Flight::get('proxy_endpoint');
				$options[CURLOPT_PROXYUSERPWD] = Flight::get('proxy_password');
				$options[CURLOPT_USERAGENT] = $this->get_user_agent('random');

			    curl_setopt_array($ch, $options);
				$body = curl_exec($ch);
				$http_info = curl_getinfo($ch);
				$ch_error = curl_error($ch);

				// Log each request
				Flight::core()->log('requests', 'Proxy Attempt #'.$attempts, $options[CURLOPT_URL].' - '.$options[CURLOPT_PROXY].' - '.$http_info['http_code'].' - '.$ch_error);

				if(!empty($body) && empty($ch_error)){
					break;
				}

			}

		}

		// Close the request
		curl_close($ch);

		// Return the response with or without headers
		if($include_headers){

			$header_string = substr($body , 0 , $http_info['header_size']);
			$body_string = substr($body , $http_info['header_size']);
			$header_parsed = $this->parse_headers($header_string);

			return json_encode(array('headers' => $header_parsed, 'request' => $options[CURLOPT_POSTFIELDS], 'body' => $body_string));

		}else{

			return $body;

		}

	}

}