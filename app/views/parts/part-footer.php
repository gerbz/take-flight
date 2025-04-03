<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php
// Scripts
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
</body>
</html>