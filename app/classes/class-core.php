<?php
// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0

Flight::register('core', 'Core');
class Core{

	/**
	* Log data to a text file
	* @param	string $filename Optional. The name of the text file.
	* @param	string $note Optional. A note about what is being logged.
	* @param	string $log Optional. Data to log after timestamp and note.
	* @return	void
	*/
	public function log($filename = 'unknown', $note = '', $log = ''){

		// Only write logs in buidl mode
		if(!Flight::get('buidl')){
			return;
		}

		$line = date("Y-m-d-H:i:s").' - '.$note."\n";
		if(!empty($log)){
			if(is_array($log)){
				$data = $log;
			}else if(substr($log, 0, 1) == '{'){
				$data = json_decode($log, true);
			}else if(preg_match('/=.*&|&.*=/i', $log)){
				$data = explode('&', $log);
			}else{
				$data = $log."\n";
			}

			$line .= print_r($data, true)."\n";
		}

		file_put_contents(Flight::get('path_logs').'/'.$filename.'.txt', $line, FILE_APPEND);

	}

	/**
	* Retrieve recent logs from a log file
	* @link		https://stackoverflow.com/a/11068324
	* @param	string $filename The name of the text file to retrieve logs from.
	* @param	int $num_lines Optional. Number of lines to return.
	* @return	array
	*/
	public function get_log($filename, $num_lines = 10){

		$file = fopen(Flight::get('path_logs').'/'.$filename.'.txt', 'r');

		fseek($file, -1, SEEK_END);

		for($line = 0, $lines = array(); $line < $num_lines && false !== ($char = fgetc($file));){

			if($char === "\n"){
				if(isset($lines[$line])){
					$lines[$line] = implode('', array_reverse($lines[$line]));
					$line++;
				}
			}else{
				$lines[$line][] = $char;
			}

			fseek($file, -2, SEEK_CUR);
		}

		fclose($file);

		if($line < $num_lines){
			$lines[$line] = implode('', array_reverse($lines[$line]));
		}

		return array_reverse($lines);
	}

	/**
	* Get a string describing how much time has lapsed since the timestamp
	*
	* ```
	* <?php
	* echo time_ago('2013-05-01 00:22:35'); // 4 months ago
	* echo time_ago('2013-05-01 00:22:35', true); // 4 months, 2 weeks, 3 days, 1 hour, 49 minutes, 15 seconds ago
	* echo time_ago('@1367367755'); //timestamp input
	* ?>
	* ```
	*
	* @link		https://stackoverflow.com/a/18602474/345599
	* @param	string $datetime Any supported date and time format
	* @param	bool $full Optional. True returns the full year, month, week, day, hour, minute, seconds
	* @return	string
	*/
	public function time_ago($datetime, $full = false){

		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);

		foreach($string as $k => &$v){
			if($diff->$k){
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			}else{
				unset($string[$k]);
			}
		}

		if(!$full){
			$string = array_slice($string, 0, 1);
		}

		if($string){
			return implode(', ', $string) . ' ago';
		}else{
			return 'just now';
		}

	}

	/**
	* Returns a random string of the given length.
	* only works on php7+, random_int is new
	* @link		https://stackoverflow.com/a/13733588/345599
	* @param	int $length How many characters should the token be.
	* @return	string
	*/
	public function generate_token($length){

		$token = '';
		$codeAlphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$codeAlphabet.= 'abcdefghijklmnopqrstuvwxyz';
		$codeAlphabet.= '0123456789';
		$max = strlen($codeAlphabet);
		if(empty($length)){$length = 64;}

		for($i=0; $i < $length; $i++){
			$token .= $codeAlphabet[random_int(0, $max-1)];
		}

		return $token;
	}

	/**
	* Check for required parameters, typically at the beginning of a route
	* Check's the POST, GET and body for the required parameter names.  If missing, adds error messages to the response.
	* @param	array $required Keys of the required parameters.
	* @return	array|false The request parameters in the given method.
	*/
	public function required($required){

		$request = array();

		if(Flight::request()->method == 'POST'){
			$request = Flight::request()->data->getData();
		}elseif(Flight::request()->method == 'GET'){
			$request = Flight::request()->query->getData();
		}else{
			 $body = Flight::request()->getBody();
			 parse_str($body, $request);
		}

		$missing = array();

		if(!empty($required)){

			foreach($required as $r){

				if(!array_key_exists($r, $request)){
					$missing[] = $r;
				}

			}

		}

		if(empty($missing)){

			return $request;

		}else{

			$missing = implode(', ', $missing);
			Flight::r()->error('Missing required parameter(s): '.$missing, __METHOD__);
			return false;

		}

	}

	/**
	* Truncates a string to a given length with ... in the middle.  Often used for ETH addresses.
	* @param	string $string The string to truncate.
	* @param	int $length How many characters to remain on either side of the ...
	* @return	str...ing
	*/
	public function truncate($string, $length = 4){
		$new_string = '';
		$new_string .= substr($string, 0, $length);
		$new_string .= '...';
		$new_string .= substr($string, -$length);
		return $new_string;
	}

	/**
	* Checks if a given string meets the requirements for an ethereum address
	* @param	string $address The ETH address
	* @return	bool
	*/
	public function is_eth_address($address){
        return preg_match('/^(0x)?[0-9a-f]{40}$/i', $address);
    }

 	/**
	* Parses the user agent to determine of the request is being made by a bot
	* @return	bool
	*/
    public function is_bot(){

	    if(empty(Flight::request()->user_agent)){
		    return true;
	    }

	    // List taken from
	    // https://www.facebook.com/robots.txt
	    // https://stackoverflow.com/a/57588908/345599
	    $bots = array(
		    'Applebot',
		    'baiduspider',
		    'Bingbot',
		    'Discordbot',
		    'facebookexternalhit',
		    'Googlebot',
		    'Googlebot-Image',
		    'ia_archiver',
		    'LinkedInBot',
		    'msnbot',
		    'Naverbot',
		    'Pinterestbot',
		    'Screaming Frog SEO Spider',
		    'seznambot',
		    'Slurp',
		    'teoma',
		    'TelegramBot',
		    'Twitterbot',
		    'Yandex',
		    'Yeti',
		    'rambler',
		    'abacho',
		    'acoi',
		    'accona',
		    'aspseek',
		    'altavista',
		    'estyle',
		    'scrubby',
		    'lycos',
		    'geona',
		    'alexa',
		    'sogou',
		    'skype',
		    'yahoo',
		    'duckduckgo',
		    'xing',
		    'bot',
		    'crawl',
		    'spider',
		    'mediapartners'
	    );

	    $bots = implode('|', $bots);

	    if(preg_match('/'.$bots.'/i', Flight::request()->user_agent)){
			return true;
		}else{
			return false;
		}

    }

}