<?php
	foreach( $banners as $banner )
	{
		?>
		<img src="<?php echo $banner->image ?>" class="img-girl" alt="<?php echo $banner->name ?>" />
		<?php
	}
?>