<?php

/**
* Include Parsedown
* @todo	Download the latest version of Parsedown and include it in /public_html/vendor/
* @link	https://github.com/erusev/parsedown
*/
require_once Flight::get('path_vendor').'/Parsedown.php';

/**
* Register the Parsedown class
*/
Flight::register('parsedown', 'Parsedown');