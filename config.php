<?php
// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0

// Flight config
Flight::set('flight.views.path', $_SERVER['DOCUMENT_ROOT'].'/../app/views');
Flight::set('flight.log_errors', true);

// Version
Flight::set('site_version', 1);

// Buidl mode
if(Flight::request()->data->buidl || strpos($_SERVER['SERVER_NAME'], 'localhost') !== FALSE){
	error_reporting(E_ERROR | E_PARSE);
	Flight::set('buidl', true);
	unset(Flight::request()->data->buidl);
	Flight::set('site_url', 'http://localhost:8000/public_html/home');
	Flight::set('site_api', 'http://localhost:8000/public_html/home');
	Flight::set('site_assets', 'http://localhost:8000/public_html/home/assets');
	Flight::set('db_name', '');
	Flight::set('db_username', '');
	Flight::set('db_password', '');
}else{
	error_reporting(0);
	Flight::set('site_url', 'https://site.com');
	Flight::set('site_api', 'https://api.site.com');
	Flight::set('site_assets', 'https://assets.site.com');
	Flight::set('db_name', '');
	Flight::set('db_username', '');
	Flight::set('db_password', '');
}

// Strings
Flight::set('string_site_name', 'Take Flight');
Flight::set('string_twitter_handle', '');

// Paths
Flight::set('path_private_html', $_SERVER['DOCUMENT_ROOT'].'/../../private_html');
Flight::set('path_home', $_SERVER['DOCUMENT_ROOT']);
Flight::set('path_app', $_SERVER['DOCUMENT_ROOT'].'/../app');
Flight::set('path_vendor', $_SERVER['DOCUMENT_ROOT'].'/../app/vendor');
Flight::set('path_assets', $_SERVER['DOCUMENT_ROOT'].'/assets');
Flight::set('path_cache', $_SERVER['DOCUMENT_ROOT'].'/../../private_html/cache');
Flight::set('path_logs', $_SERVER['DOCUMENT_ROOT'].'/../../private_html/logs');
Flight::set('path_data', $_SERVER['DOCUMENT_ROOT'].'/../../private_html/data');

// Twitter
Flight::set('twitter_apikey', '');
Flight::set('twitter_apikey_secret', '');
Flight::set('twitter_app_token', '');

// Proxy
Flight::set('proxy_endpoint', '');
Flight::set('proxy_password', '');

// Pushover
Flight::set('pushover_app', '');
Flight::set('pushover_group_everyone', '');
