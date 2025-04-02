<?php
// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0

Flight::register('r', 'Response');
class Response{

	/**
	* Start the system block, which is returned with every response
	* Includes a status, message, errors and a timestamp (now)
	*/
	public $response = array('_system' => array());

	/**
	* Allow primary domain to make ajax requests with the subdomain
	* Turned on in Application Settings, but still doesn't work without this
	*/
	public function __construct(){

		//
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
		header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
		header("Content-Type: application/json");

	}

	/**
	* Output a json response with status 200
	* @param	string $message Optional. A message to be displayed to the user.  Use r()->error() for messages not displayed to the user.
	* @return	void Doesn't return, it outputs the response and exits!
	*/
	public function success($message = ''){

		// Setup system block
		$this->response['_system']['status'] = 200;
		$this->response['_system']['now'] = Flight::cache()->timestamp;

		if(!empty($this->response['_system']['message'])){

			$this->response['_system']['message'] = $message.' '.$this->response['_system']['message'];

		}else{

			$this->response['_system']['message'] = $message;

		}

		// Set headers
		http_response_code($this->response['_system']['status']);

		// Respond
		exit(Flight::json($this->response));

	}

	/**
	* Output a json response with fail status
	* @param	int $status_code Typically 400
	* @param	string $message Optional. A message to be displayed to the user.  Use r()->error() for messages not displayed to the user.
	* @return	void Doesn't return, it outputs the response and exits!
	*/
	public function fail($status_code, $message = ''){

		// Setup system block
		$this->response['_system']['status'] = $status_code;
		$this->response['_system']['now'] = Flight::cache()->timestamp;

		if(empty($this->response['_system']['message'])){

			$this->response['_system']['message'] = $message;

		}

		// Set the headers
		http_response_code($this->response['_system']['status']);

		// Respond
		exit(Flight::json($this->response));

	}

	/**
	* Add data to the response object
	* @param	array $array The data you want to inclode in the response
	* @return	array The full response object after adding this new data
	*/
	public function set($array){

		// It must be an array
		if(!is_array($array)){
			$array = array($array);
		}

		// Handle multidimensional arrays differently
		if(count($array) != count($array, COUNT_RECURSIVE)){

			$this->response = array_merge_recursive($this->response, $array);

		}else{

			$this->response = array_merge($this->response, $array);

		}

		return $this->response;

	}

	/**
	* Add an error to the _system block. Only in dev environment and never displayed to the user.
	* @param	string $error The error message
	* @param	constant $method Optional. Include __METHOD__ as the second parameter to report where the error came from in the message.
	* @return	array The full response object after adding this error.
	*/
	public function error($error, $method){

		if(!Flight::get('buidl')){
			return;
		}

		if(!empty($method)){
			$error = $method.' - '.$error;
		}

		$response['_system']['errors'][] = $error;

		return $this->set($response);
	}

	/**
	* Add a message to be displayed to the user. Concatenated to any previous messages.
	* @param	string $message The message to add.
	* @return	string The new message.
	*/
	public function message($message){

		if(empty($this->response['_system']['message'])){

			$this->response['_system']['message'] = $message;

		}else{

			$this->response['_system']['message'] = $this->response['_system']['message'].' '.$message;

		}

		return $this->response['_system']['message'];

	}

}