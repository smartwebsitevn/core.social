<?php
$_slide_data = function ($items) {
	ob_start() ?>
	<?php if ($items): ?>
		<?php foreach ($items as $i => $item): ?>
			<div  class="item" style="background-image: url(<?php echo $item->image->url; ?>)" data-dot="<?php echo ($i+1) ?>">
				<?php  if(isset($item->desc)&& $item->desc): ?>
					<div class="caption">
						<strong class="title"><?php echo $item->target ?></strong>
						<p class="des"><?php echo $item->desc ?></p>
					</div>
				<?php endif;  ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php return ob_get_clean();
};
?>
<div class="slide-banner">
		<div class="owl-carousel owl-nav-auto">
			<?php echo $_slide_data($items); ?>
		</div>
</div>
