<?php
// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0

/**
* Include Pushover
* @todo	Download the latest version of Pushover and include it in /public_html/vendor/
* @todo	Set pushover_app, pushover_group_everyone in config.php
*/
require_once Flight::get('path_vendor').'/Pushover.php';

/**
* Register the Push class
*/
Flight::register('push', 'Push');
class Push extends Pushover{

	/**
	* Send a notification to the team using the Pushover API
	* Only in production
	*
	* @link		https://github.com/kryap/php-pushover
	* @param	string $title Of the notification
	* @param	string $msg Optional. Body of the notification
	* @param	bool $priority Optional. Whether to set as important
	* @param	string $url Optional. Clickable URL in the body of the notification
	* @return	bool Did it work?
	*/
	public function send($title, $msg = '', $priority = false, $url = null){

		if(Flight::get('buidl')){
			return true;
		}

		Pushover::setUser( Flight::get('pushover_group_everyone') );
		Pushover::setToken( Flight::get('pushover_app') );
		Pushover::setTitle($title);
		Pushover::setUrl($url);
		Pushover::setMessage($msg);
		Pushover::setSound('magic');

		if($priority){
			Pushover::setPriority($priority);
		}

		return Pushover::send();

	}

}