<?php echo $part_head; ?>
<body id="page-blog-post" class="page">
<?php echo $part_nav; ?>

<div class="container page-container">
	
<div class="row">
	<div class="col-md-offset-2 col-md-8 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10">
		
	<ol class="breadcrumb">
		<li><a href="<?php echo Flight::get('site_url');?>">Home</a></li>
		<li><a href="<?php echo Flight::get('site_url');?>/blog/">Blog</a></li>
		<li class="active"><a href="<?php echo Flight::get('site_url').'/blog/'.$post['slug'].'/';?>"><?php echo $post['page_title'];?></a></li>
	</ol>

	<?php Flight::render('blog/'.$post['slug'], array('post' => $post)); ?>

	<hr>
	
	<p class="author"><?php echo 'By <a href="'.Flight::get('site_url').'/'.$post['author'].'">'.$post['author'].'</a> on '.$post['date'];?></p>
	
	<a class="btn btn-default share-btn" href="<?php echo 'https://x.com/intent/tweet?text='.urlencode($post['page_title']).'&url='.urlencode(Flight::get('site_url').'/blog/'.$post['slug'].'/');?>">Share on X</a>

	</div>
</div>

</div>
</body>
</html>