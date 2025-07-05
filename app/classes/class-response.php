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
	public $timestamp;

	/**
	* Allow primary domain to make ajax requests with the subdomain
	* Turned on in Application Settings, but still doesn't work without this
	*/
	public function __construct(){

		// Allow primary domain to make ajax requests with the subdomain
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Credentials: true");
		header("Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT");
		header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
		header("Content-Type: application/json");

		// Set a timestamp once so its the same regardless of processing time.
		$this->timestamp = time();

	}

	/**
	* Replace null values with empty strings
	* @param	mixed $value The value to replace null values with empty strings
	* @return	mixed The value with null values replaced with empty strings
	*/
	public function replace_null_with_empty_string($value){

		if(is_array($value)){
			return array_map(array($this, 'replace_null_with_empty_string'), $value);
		}

		return $value === null ? '' : $value;

	}

	/**
	* Output a json response with status 200
	* @param	string $message Optional. A message to be displayed to the user.  Use r()->error() for messages not displayed to the user.
	* @param	bool $exit Optional. Whether to exit after sending response. Defaults to true.
	* @return	void|string Doesn't return if exit=true, otherwise returns the JSON response
	*/
	public function success($message = '', $exit = true){

		// Setup system block
		$this->response['_system']['status'] = 200;
		$this->response['_system']['now'] = $this->timestamp;

		// Include a message if it's set
		if(!empty($message)){
			$this->message($message);
		}

		// Set headers
		http_response_code($this->response['_system']['status']);

		// Respond
		$this->response = $this->replace_null_with_empty_string($this->response);
		
		if($exit){
			exit(Flight::json($this->response));
		}else{
			echo Flight::json($this->response);
			
			// Ensure the HTTP response is sent and connection closed immediately
			if (function_exists('fastcgi_finish_request')) {
				fastcgi_finish_request();
			} else {
				ob_end_flush();
				flush();
			}
			
			return $this->response;
		}

	}

	/**
	* Output a json response with fail status
	* @param	string $message Optional. A message to be displayed to the user.  Use r()->error() for messages not displayed to the user.
	* @param	int $status_code Typically 400
	* @param	bool $exit Optional. Whether to exit after sending response. Defaults to true.
	* @return	void|string Doesn't return if exit=true, otherwise returns the JSON response
	*/
	public function fail($message = '', $status_code = 400, $exit = true){

		// Setup system block
		$this->response['_system']['status'] = $status_code;
		$this->response['_system']['now'] = $this->timestamp;

		// Include a message if it's set
		if(!empty($message)){
			$this->message($message);
		}

		// Set the headers
		http_response_code($this->response['_system']['status']);

		// Respond
		$this->response = $this->replace_null_with_empty_string($this->response);
		
		if($exit){
			exit(Flight::json($this->response));
		}else{
			echo Flight::json($this->response);
			
			// Ensure the HTTP response is sent and connection closed immediately
			if (function_exists('fastcgi_finish_request')) {
				fastcgi_finish_request();
			} else {
				ob_end_flush();
				flush();
			}
			
			return $this->response;
		}

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