<?php
// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0

Flight::register('cache', 'Cache');
class Cache{

	public $timestamp;

	/**
	* Set a timestamp once so its the same regardless of processing time.
	*/
	public function __construct(){

		$this->timestamp = time();

	}

	/**
	* Retrieve a file from the cache.
	* @param	string $filename Typically username.eth
	* @return	array|false Decoded json.
	*/
	public function get($filename){

		$filename = strtolower($filename);
		$file = Flight::get('path_cache').'/'.$filename.'.json';
		$data = file_get_contents($file, true);
		$json = json_decode($data, true);

		if(empty($json)){
			return false;
		}else{
			return $json;
		}

	}

	/**
	* Creates or updates a cache file (if not a bot)
	* * Creates the object in the cache if it doesn't exist
	* * Replaces the object in the cache if it exists
	* * Set multiple objects/data at once if the object (and data) is an array
	*
	* @param	string $filename Typically username.eth
	* @param	string|array $object Key(s) correspindinding to data(s)
	* @param	string|array $data Values(s) correspindinding to objects(s)
	* @return	array The contents of cache in it's entirety.	
	*/
	public function set($filename, $object, $data){

		$filename = strtolower($filename);

		// $data must be an array
		if(!is_array($data)){
			$data = array($data);
		}

		// Wrap object and data in an array if its not
		if(!is_array($object)){
			$object = array($object);
			$data = array($data);
		}

		// Get the cache
		$cache = $this->get($filename);

		// If cache is empty create it
		if(empty($cache)){
			$created = true;
			$cache = array('created' => $this->timestamp);
		}

		$i = 0;
		foreach($object as $ob){

			// Always update the timestamp
			$cache[$ob]['timestamp'] = $this->timestamp;

			// Only update the data if we have some
			if(!empty($data[$i])){
				$cache[$ob]['data'] = $data[$i];
			}

			$i++;
		}

		if(!Flight::core()->is_bot()){

			$json = json_encode($cache);

			$file = Flight::get('path_cache').'/'.$filename.'.json';
			file_put_contents($file, $json);

			// Optionally do something when the cache is created
			if($created){

			}

		}

		return $cache;

	}
	
	/**
	* Delete a file from the cache
	* @param	string $filename Typically username.eth
	* @return	bool
	*/
	public function delete($filename){

		if(unlink(Flight::get('path_cache').'/'.$filename.'.json')){
			return true;
		}else{
			return false;
		}

	}
	
	/**
	* Check if cached data is outdated
	* We don't want to bang on API's with every page refresh, which is why cache exists in the first place. So we only refresh if the cache is old.
	* @param	string $type Cache key to check
	* @param	int $time Timestamp to check
	* @return	bool
	*/
	public function is_old($type, $time){

		if(empty($time)){
			return true;
		}

		$diff = $this->timestamp - $time;

		$minute = 60;
		$hour = 3600;
		$day = 86400;

		switch($type){

			case 'type1':
				if($diff > $minute){
					return true;
				}
			break;
			case 'type2':
				if($diff > $hour){
					return true;
				}
			break;
			case 'type3':
				if($diff > $day){
					return true;
				}
			break;
			default:
				return false;
			break;

		}

	}

}