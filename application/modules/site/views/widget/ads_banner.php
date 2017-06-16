<?php if ($banners):
	//pr($banners);
	$_data_banner = function ($banner) {
		ob_start(); ?>
		<a href="javascript:void(0)" class="item do_action" data-loader="_"
		   data-url="<?php echo site_url('banner/click/' . $banner->id) ?>" rel="nofollow"
		   style="display:block!important;">
			<img src="<?php echo $banner->image->url; ?>">
		</a>
		<?php return ob_get_clean();

	};

	$style = $widget->setting["type"];
	?>
	<?php if ($style == 'default'): ?>
	<?php foreach ($banners as $banner): ?>
		<?php echo $_data_banner($banner) ?>
	<?php endforeach; ?>
<?php elseif ($style == 'content'): ?>
	<?php foreach ($banners as $banner): ?>
		<div class="row block-qc-img text-center mt10 mb10">
			<?php echo $_data_banner($banner) ?>
		</div>
	<?php endforeach; ?>
<?php elseif ($style == 'top'): ?>
	<div class="aside ad-banner-top">
		<h4 class="aside-title"><?php echo $widget->name ?></h4>
		<?php foreach ($banners as $banner): ?>
			<div class="aside-block">
				<?php echo $_data_banner($banner) ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif ?>

<?php endif ?>