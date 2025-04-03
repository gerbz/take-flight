<?php
// Copyright 2022 3bra.com
// SPDX-License-Identifier: Apache-2.0

/**
* Render the 404 page. Which at the moment, there isn't really one...
*
* @see		/app/views/404.php
*/
Flight::map('notFound', function(){
	
	$tags = array(
		'title' => 'Someone\'s lost...',
		'description' => '',
		'image' => '404.png',
		'styles' => array(
			array('id'=>'css-notfound','href'=>Flight::get('site_assets').'/styles/notfound.css?v='.Flight::get('site_version'))
		),
		'scripts' => array(
			array('id'=>'js-notfound','src'=>Flight::get('site_assets').'/scripts/notfound.js?v='.Flight::get('site_version').'','integrity'=>'','crossorigin'=>'')
		),
	);
	
	Flight::render('parts/part-head.php', array('title' => $tags['title'], 'description' => $tags['description'], 'image' => $tags['image'], 'styles' => $tags['styles'], 'scripts' => $tags['scripts']), 'part_head');
	Flight::render('parts/part-nav.php', array(), 'part_nav');
	Flight::render('parts/part-footer.php', array(), 'part_footer');
	Flight::render('404.php', array('title' => $tags['title']));
	
});

/**
* Render the homepage
*
* @see		/app/views/home.php
*/
Flight::route('/', function(){

	$tags = array(
		'title' => 'New site',
		'description' => 'Description of new site',
		'image' => 'homepage.png',
		'styles' => array(
			array('id'=>'css-home','href'=>Flight::get('site_assets').'/styles/home.css?v='.Flight::get('site_version'))
		),
		'scripts' => array(
			array('id'=>'js-home','src'=>Flight::get('site_assets').'/scripts/home.js?v='.Flight::get('site_version').'','integrity'=>'','crossorigin'=>'')
		),
	);

	Flight::render('parts/part-head.php', array('title' => $tags['title'], 'description' => $tags['description'], 'image' => $tags['image'], 'styles' => $tags['styles'], 'scripts' => $tags['scripts']), 'part_head');
	Flight::render('parts/part-nav.php', array(), 'part_nav');
	Flight::render('parts/part-footer.php', array(), 'part_footer');
	Flight::render('home.php');

});

/**
* Render the blog and blog posts
* Includes the metadata for all posts as well
* This is obviously not sustainable, but works for now
*
* @see		/app/views/blog.php
* @see		/app/views/blog-post.php
*/
Flight::route('/blog(/@post)', function($post){

	// Add posts chronologically from newest to oldest
	$tags = array(
		'title' => 'Site Blog',
		'description' => 'Blog description here',
		'page_title' => 'Site Blog',
		'image' => 'blog.png',
		'styles' => array(
			array('id'=>'css-post','href'=>Flight::get('site_assets').'/styles/blog.css?v='.Flight::get('site_version'))
		),
		'scripts' => array(
			array('id'=>'js-post','src'=>Flight::get('site_assets').'/scripts/blog.js?v='.Flight::get('site_version').'','integrity'=>'','crossorigin'=>'')
		),
	);
	
	// Get all the posts
	$posts_file = Flight::get('path_app').'/views/blog/blog.json';
	$posts_json = file_get_contents($posts_file, true);
	$posts = json_decode($posts_json, true);

	if(empty($post)){

		Flight::render('parts/part-head.php', array('title' => $tags['title'], 'description' => $tags['description'], 'image' => $tags['image'], 'styles' => $tags['styles'], 'scripts' => $tags['scripts']), 'part_head');
		Flight::render('parts/part-nav.php', array(), 'part_nav');
		Flight::render('parts/part-footer.php', array(), 'part_footer');
		Flight::render('blog.php', array('posts' => $posts['posts']));

	}else{

		Flight::render('parts/part-head.php', array('title' => $posts['posts'][$post]['title'], 'description' => $posts['posts'][$post]['description'], 'image' => $posts['posts'][$post]['image'], 'styles' => $tags['styles'], 'scripts' => $tags['scripts']), 'part_head');
		Flight::render('parts/part-nav.php', array(), 'part_nav');
		Flight::render('parts/part-footer.php', array(), 'part_footer');
		Flight::render('blog-post.php', array('post' => $posts['posts'][$post]));

	}

});

/**
* Render the changelog
*
* @see		/app/views/changelog.php
*/
Flight::route('/changelog', function(){

	$tags = array(
		'title' => 'Changelog',
		'description' => 'The evolution of Take Flight and improvements to come.',
		'page_title' => 'Changelog',
		'image' => 'changelog.png',
		'styles' => array(
			array('id'=>'css-post','href'=>Flight::get('site_assets').'/styles/changelog.css?v='.Flight::get('site_version'))
		),
		'scripts' => array(
			array('id'=>'js-post','src'=>Flight::get('site_assets').'/scripts/changelog.js?v='.Flight::get('site_version').'','integrity'=>'','crossorigin'=>'')
		),
	);

	Flight::render('parts/part-head.php', array('title' => $tags['title'], 'description' => $tags['description'], 'image' => $tags['image'], 'styles' => $tags['styles'], 'scripts' => $tags['scripts']), 'part_head');
	Flight::render('parts/part-nav.php', array(), 'part_nav');
	Flight::render('parts/part-footer.php', array(), 'part_footer');
	Flight::render('changelog.php');
	
});