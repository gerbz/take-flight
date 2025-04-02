<?php echo $part_head; ?>
<body id="page-blog" class="page">
<?php echo $part_nav; ?>

<div class="container page-container">
	
<div class="row">
	<div class="col-md-offset-2 col-md-8 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10">
	<?php
		foreach($posts as $key => $post){
			echo '<h3><a href="'.Flight::get('site_url').'/blog/'.$post['slug'].'/">'.$post['page_title'].'</a></h3>';
			echo '<p class="author">By <a href="'.Flight::get('site_url').'/'.$post['author'].'">'.$post['author'].'</a> on '.$post['date'].'</p>';
			
			echo '<p>'.$post['description'].'</p>';
		}	
	?>
	</div>
</div>

</div>
</body>
</html>