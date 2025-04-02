<?php
// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0

// Include Flight & Config first
require_once '../app/vendor/flight/Flight.php';
require_once '../../private_html/config.php';

// Include classes
foreach(glob(Flight::get('path_app').'/classes/*.php') as $myclasses){
	require_once $myclasses;
}

// Include routes
foreach(glob(Flight::get('path_app').'/routes/*.php') as $myroutes){
	require_once $myroutes;
}

// Take flight
Flight::start();