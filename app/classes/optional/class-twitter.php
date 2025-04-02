<?php
// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0

/**
* Register the Twitter class
* @todo	Set twitter_apikey, twitter_apikey_secret, twitter_app_token in config.php
*/
Flight::register('twitter', 'Twitter');
class Twitter{

	private $twitter_endpoint = 'https://api.twitter.com/2/';
	private $twitter_endpoint_auth = 'https://api.twitter.com/oauth2/token';

	/**
	* Convert a Unix epoch timestamp into a format Twitter understands
	*
	* @link		https://developer.twitter.com/en/docs/twitter-ads-api/timezones
	* @link		https://stackoverflow.com/a/43191888
	* @param	string $timstamp Unix epoch timestamp. Example: 1661789384
	* @return	string example: 2022-09-15T17:06:47+00:00
	*/
	public function time_to_twitter_time($timstamp){
		return date_format(date_create('@'. $timstamp), 'c');
	}

	/**
	* Convert a Twitter's returned time into a Unix epoch timestamp
	*
	* @link		https://developer.twitter.com/en/docs/twitter-ads-api/timezones
	* @link		https://stackoverflow.com/a/43191888
	* @param	string $timstamp Unix epoch timestamp. Example: 2022-09-15T17:06:47+00:00
	* @return	string example: 1661789384
	*/
	public function twitter_time_to_time($timstamp){
		return strtotime($timstamp);
	}

	/**
	* Format a twitter username
	* When a user gives you their username, it can come in all sorts of formats
	* This strips, urls, paths, query parameters @ symbols, etc.
	*
	* @param	string $username Example: @0x3bra
	* @return	string Example: 0x3bra
	*/
	public function format_username($username){

		// Everything gets spaces and @ removed
		$username = str_replace(array('@',' '), '', $username);

		// Parse the extra
		// BugFix:  Everything gets a "scheme" because if no scheme, then parse_url doesn't work
		if(strtolower(mb_substr($username, 0, 4)) !== 'http'){
			$extrahttps = 'https://' . $username;
			$link = parse_url($extrahttps);
		}else{
			$link = parse_url($username);
		}

		if(in_array(strtolower($link['host']), array('twitter.com','www.twitter.com', 'mobile.twitter.com', 'x.com', 'www.x.com', 'mobile.x.com'))) {
			$username = str_replace('/', '', $link['path']);
		}

		if(preg_match('/^[a-zA-Z0-9_]{1,15}$/', $username)){

			return strtolower($username);

		}else{

			return false;

		}

	}

	/**
	* Get a user's profile information from the Twitter API
	*
	* @link		https://developer.twitter.com/en/docs/twitter-api/users/lookup/api-reference/get-users-by-username-username
	* @param	string $username The username of the user to lookup
	* @return	array|false Just the fields we requested.
	*/
	public function get_user($username){

		$endpoint = $this->twitter_endpoint.'users/by/username/'.$username;
		$headers = array('Authorization' => 'Bearer '.Flight::get('twitter_app_token'));
		$data = array('user.fields' => 'id,name,username,verified');

		$response_json = Flight::call()->api($endpoint, $data, 'GET', $headers);
		$response = json_decode($response_json, true);

		if(empty($response['data'])){
			return false;
		}else{
			return $response['data'];
		}

	}

	/**
	* Get a user's tweets from the Twitter API
	*
	* @link		https://developer.twitter.com/en/docs/twitter-api/tweets/timelines/api-reference/get-users-id-tweets
	* @param	string $user_id The id of the user to lookup. Have to use twitter()->get_user() to find it first.
	* @return	array|false Tweets including just the fields we requested.
	*/
	public function get_user_tweets($user_id, $since = false){

		$endpoint = $this->twitter_endpoint.'users/'.$user_id.'/tweets';
		$headers = array('Authorization' => 'Bearer '.Flight::get('twitter_app_token'));

		$data = array(
			'exclude' => 'retweets',
			'expansions' => 'author_id,attachments.media_keys,in_reply_to_user_id,referenced_tweets.id',
			'media.fields' => 'preview_image_url,type,url',
			'tweet.fields' => 'attachments,created_at,entities,in_reply_to_user_id,referenced_tweets',
			'user.fields' => 'id,name,username,verified'
		);

		if($since){
			$since = $since +1; //Response is inclusive of that time
			$data['start_time'] = $this->time_to_twitter_time($since);
		}

		$response_json = Flight::call()->api($endpoint, $data, 'GET', $headers);
		$response = json_decode($response_json, true);

		if(empty($response['data'])){
			return false;
		}else{
			return $response;
		}

	}

	/**
	* Get a list of specific tweets from the Twitter API
	*
	* @link		https://developer.twitter.com/en/docs/twitter-api/tweets/lookup/api-reference/get-tweets
	* @param	string $ids comma separated list of tweet ids to lookup
	* @return	array|false Tweets including just the fields we requested.
	*/
	public function get_tweets($ids){

		$endpoint = $this->twitter_endpoint.'tweets';
		$headers = array('Authorization' => 'Bearer '.Flight::get('twitter_app_token'));

		$data = array(
			'ids' => $ids,
			'expansions' => 'author_id,attachments.media_keys,in_reply_to_user_id,referenced_tweets.id',
			'media.fields' => 'preview_image_url,type,url',
			'tweet.fields' => 'attachments,created_at,entities,in_reply_to_user_id,referenced_tweets',
			'user.fields' => 'id,name,username,verified'
		);

		$response_json = Flight::call()->api($endpoint, $data, 'GET', $headers);
		$response = json_decode($response_json, true);

		if(empty($response['data'])){
			return false;
		}else{
			return $response;
		}

	}

	/**
	* Tweet data returned from the Twitter API is a bit fragmented
	* This function cleans it up, puts everything needed for a tweet IN the tweet and formats the response
	*
	* @param	array $tweets the response from twitter()->get_user_tweets() or twitter()->get_tweets
	* @return	array The same tweets put in, except formatted nicely
	*/
	public function format_tweets_for_feed($tweets){

		$feed = array();

		foreach($tweets['data'] as $tweet){

			$time = $this->twitter_time_to_time($tweet['created_at']);
			$feed[$time] = $tweet;
			$feed[$time]['type'] = 'tweet';
			$feed[$time]['time_at'] = $time;

			if(!empty($tweet['referenced_tweets'])){

				$r = 0;
				foreach($tweet['referenced_tweets'] as $ref){

					foreach($tweets['includes']['tweets'] as $itweet){

						if($itweet['id'] == $ref['id']){
							$feed[$time]['referenced_tweets'][$r]['tweet'] = $itweet;
						}

					}

					$r++;

				}

			}

			if(!empty($tweet['attachments']['media_keys'])){

				foreach($tweet['attachments']['media_keys'] as $media){

					foreach($tweets['includes']['media'] as $imedia){

						if($media == $imedia['media_key']){
							$feed[$time]['attachments'][$imedia['media_key']] = $imedia;
						}

					}

				}

			}

		}

		return $feed;

	}

}