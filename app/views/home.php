<?php echo $part_head; ?>
<body id="page-home" class="page">
<?php echo $part_nav; ?>

<div class="container-fluid text-center py-5 bg-light">
	<h1 class="display-4">Meet Your New Website</h1>
	<h3 class="text-muted">Powered by Flight and Bootstrap 5</h3>
</div>

<div class="container py-4 offset-md-2 col-md-8 offset-sm-1 col-sm-10 offset-1 col-10">
	<?php 
	$readme = file_get_contents(Flight::get('path_home').'../readme.md');
	echo Flight::parsedown()->text($readme);
	?>
</div>

<?php echo $part_footer; ?>