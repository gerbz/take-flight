<?php echo $part_head; ?>
<body id="page-blog-post" class="page">
<?php echo $part_nav; ?>

<div class="container page-container py-4">
	
<div class="row">
	<div class="offset-md-2 col-md-8 offset-sm-1 col-sm-10 offset-1 col-10">
		
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="<?php echo Flight::get('site_url');?>">Home</a></li>
			<li class="breadcrumb-item"><a href="<?php echo Flight::get('site_url');?>/blog/">Blog</a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $post['page_title'];?></li>
		</ol>
	</nav>

	<?php Flight::render('blog/'.$post['slug'], array('post' => $post)); ?>

	<hr class="my-4">
	
	<p class="author"><?php echo 'By <a href="'.$post['author_url'].'">'.$post['author'].'</a> on '.$post['date'];?></p>
	
	<a class="btn btn-outline-secondary share-btn" href="<?php echo 'https://x.com/intent/tweet?text='.urlencode($post['page_title']).'&url='.urlencode(Flight::get('site_url').'/blog/'.$post['slug'].'/');?>">Share on X</a>

	</div>
</div>

</div>

<?php echo $part_footer; ?>