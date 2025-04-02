<!DOCTYPE html>
<html>
<head>
<?php
if(Flight::get('buidl')){
	echo '<meta name="robots" content="noindex">';
}
?>	
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo Flight::get('site_assets');?>/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo Flight::get('site_assets');?>/favicons/favicon-14-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo Flight::get('site_assets');?>/favicons/favicon-14-16x16.png">
<meta name="theme-color" content="#ffffff">
<?php
// Default tags
echo '<title>'.htmlentities($title).'</title>'."\n";
echo '<meta name="description" description="'.htmlentities($description).'">'."\n";

// Twitter tags
echo '<meta name="twitter:card" content="summary_large_image">'."\n";
echo '<meta name="twitter:site" content="@'.Flight::get('string_twitter_handle').'">'."\n";
echo '<meta name="twitter:title" content="'.htmlentities($title).'">'."\n";
echo '<meta name="twitter:description" content="'.htmlentities($description).'">'."\n";
echo '<meta name="twitter:image" content="'.Flight::get('site_assets').'/images/og/'.$image.'">'."\n";

// OG tags
echo '<meta property="og:title" content="'.htmlentities($title).'">'."\n";
echo '<meta property="og:description" content="'.htmlentities($description).'">'."\n";
echo '<meta property="og:site_name" content="'.Flight::get('string_site_name').'">'."\n";
echo '<meta property="og:url" content="'.Flight::get('site_url').$path.'">'."\n";
echo '<meta property="og:image" content="'.Flight::get('site_assets').'/images/og/'.$image.'">'."\n";
echo '<meta property="og:image:secure_url" content="'.Flight::get('site_assets').'/images/og/'.$image.'" />'."\n";
echo '<meta property="og:image:type" content="image/png">'."\n";
echo '<meta property="og:image:width" content="1330" />'."\n";
echo '<meta property="og:image:height" content="700" />'."\n";
echo '<meta property="og:image:alt" content="'.htmlentities($title).'">'."\n";

// Styles
echo '<link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">';
echo '<link type="text/css" rel="stylesheet" href="'.Flight::get('site_assets').'/styles/site.css?v='.Flight::get('site_version').'">';
if($styles){
	foreach($styles as $style){	
		$p = '';
		foreach($style as $key => $value){
			if(empty($value)){
				$p .= $key.' ';
			}else{
				$p .= $key.'="'.$value.'" ';
			}		
		}
		echo '<link type="text/css" rel="stylesheet" '.$p.'>'."\n";
	}
}

// Scripts
echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript" id="jquery" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" type="text/javascript" id="bootstrap" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>';
echo '<script type="text/javascript" src="'.Flight::get('site_assets').'/scripts/site.js?v='.Flight::get('site_version').'"></script>';
if($scripts){
	foreach($scripts as $script){
		$p = '';
		foreach($script as $key => $value){
			if(empty($value)){
				$p .= $key.' ';
			}else{
				$p .= $key.'="'.$value.'" ';
			}
		}
		echo '<script type="text/javascript" '.$p.'></script>'."\n";
	}
}
?>
</head>
