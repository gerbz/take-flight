<?php echo $part_head; ?>
<body id="page-blog" class="page">
<?php echo $part_nav; ?>

<div class="container page-container py-4">
	
<div class="row">
	<div class="offset-md-2 col-md-8 offset-sm-1 col-sm-10 offset-1 col-10">
	<?php
		foreach($posts as $key => $post){
			echo '<h3><a href="'.Flight::get('site_url').'/blog/'.$post['slug'].'/">'.$post['page_title'].'</a></h3>';
			echo '<p class="author">By <a href="'.$post['author_url'].'">'.$post['author'].'</a> on '.$post['date'].'</p>';			
			echo '<p>'.$post['description'].'</p>';
		}	
	?>
	</div>
</div>

</div>

<?php echo $part_footer; ?>