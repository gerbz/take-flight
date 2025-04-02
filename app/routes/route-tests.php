<?php
// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0
	
Flight::route('/test', function(){
	
	//$d = $_SERVER['SERVER_NAME'];

	/* CACHE */
	//$d = Flight::cache()->delete('file1');
	//$d = Flight::cache()->get(file1);
	
	/* CALL API */	
	//$d = Flight::call()->get_user_agent('random');
	//$d = Flight::call()->api(Flight::get('site_api').'/ip', array('test'=>'true','hello'=>'world'), 'POST', array('Content-Type'=>'application/x-www-form-urlencoded'), array(), false, false);
	
	/* CORE */	
	//$d = Flight::core()->generate_token(10);
	//$d = Flight::core()->get_log('requests');
	//$d = Flight::core()->time_ago('@1367367755', true);
	//$d = Flight::core()->log('text','note here','data to log');
	
	/* PUSH */
	//$d = Flight::push()->send('New test', 'This is the message', 0, 'https://site.com');
	
	/* Response */
	//Flight::r()->set(array('key0' => 'value0'));
	//Flight::r()->set(array('key1' => array('key1b' => 'value1')));
	//Flight::r()->error('This is an error', __METHOD__);
	//Flight::r()->message('This is a message');
	//Flight::r()->success();	

	/* TWITTER */
	//$d = Flight::twitter()->twitter_time_to_time('2022-09-15T17:06:47+00:00');
	//$d = Flight::twitter()->time_to_twitter_time('1661789384');	
	//$user = Flight::twitter()->get_user('0x3bra');
	//$tweets = Flight::twitter()->get_user_tweets($user['id']);
	//$d = Flight::twitter()->get_tweets('1553148723097522176,1550550615629053952');
	//$d = Flight::twitter()->format_tweets_for_feed($tweets);

	//Flight::r()->set($d);
	//Flight::r()->success();	
	echo '<pre>';print_r($d);
	//echo 'hello world!';
	
	
});

Flight::route('/ip', function(){

	echo '<pre>Flight REQUEST - ';print_r(Flight::request());
	echo '<pre>REQUEST DATA - ';print_r($_REQUEST);
	echo '<pre>POST DATA - ';print_r($_POST);
	echo '<pre>GET DATA - ';print_r($_GET);
});

Flight::route('/phpinfo', function(){

	phpinfo();
	
});
