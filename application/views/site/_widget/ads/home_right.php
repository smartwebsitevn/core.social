<?php
	foreach( $banners as $banner )
	{
		?>
		<img src="<?php echo $banner->image ?>" class="img-boy" alt="<?php echo $banner->name ?>" />
		<?php
	}
?>