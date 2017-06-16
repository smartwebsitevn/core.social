<!-- Slide -->
<div class="owl-carousel-top">
	<?php foreach ($items as $i => $item): ?>
		<div class="carousel-item">
			<a href="<?php echo $item->url ?>"><img alt="images"  src="<?php echo $item->image->url; ?>"/></a>
			<?php if(isset($item->desc)&& $item->desc): ?>
			<div class="content-slide">
				<div class="cus-slide1">
					<?php echo handle_content($item->desc,"output") ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>

</div>

