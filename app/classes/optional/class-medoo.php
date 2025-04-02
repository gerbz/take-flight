<?php
// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0

/**
* Include Medoo
* @todo	Download the latest version of Medoo and include it in /public_html/vendor/
* @todo	Set db_name, db_username, db_password in config.php
* @todo	Must bang out 'namespace Medoo;' in Medoo.php to get this working!
*/
require_once Flight::get('path_vendor').'/Medoo.php';

/**
* Register the Medoo framework with our configurations
* @link		https://medoo.in/api/new
* @param	type $var Optional. Description.
* @return	type Description.
*/
Flight::register('db', 'medoo', array([

	//required
	'type' => 'mysql',
	'host' => 'localhost',
	'database' => Flight::get('db_name'),
	'username' => Flight::get('db_username'),
	'password' => Flight::get('db_password'),

	//optional
	'charset' => 'utf8mb4',
	'collation' => 'utf8_unicode_520_ci',
	'port' => 3306,
	'option' => [
		PDO::ATTR_CASE => PDO::CASE_NATURAL
	]

]));
