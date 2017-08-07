<?php if($banner): ?>
<?php $setting=$widget->setting; ?>
<?php if($setting["type"] =='content'): ?>
	<div class="row block-qc-img text-center mt10 mb10">
		<a href="javascript:void(0)" class="item do_action" data-loader="_" data-url="<?php echo site_url('banner/click/'.$banner->id) ?>" rel="nofollow" style="display:block!important;"	>
			<img src="<?php echo $banner->image->url; ?>">
		</a>
	</div>

<?php else: ?>
	<a href="javascript:void(0)" class="item do_action" data-loader="_" data-url="<?php echo site_url('banner/click/'.$banner->id) ?>" rel="nofollow" style="display:block!important;"	>
		<img src="<?php echo $banner->image->url; ?>">
	</a>
<?php endif ?>
<?php endif ?>