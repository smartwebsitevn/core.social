<section class="work-ad slide-blog mb0">
	<div class="container">
		<div class="heading"><?php echo $widget->name ?></div>
		<?php if (isset($list) && $list): ?>
			<div class="slide-blog">
				<div class="owl-carousel">
					<?php foreach($list as $row){?>
						<div class="item-category-blog">
							<div class="img">
								<a href="<?php echo $row->_url_view?>" title="<?php echo $row->name ?>">
									<img class="img-responsive" src="<?php echo thumb_img($row->image)//$row->image->url_thumb; ?>">
								</a>
							</div>
							<div class="caption">
								<a href="<?php echo $row->_url_view ?>" class="name" title="<?php echo $row->name ?>"><?php echo $row->name ?></a>
								<span><?php echo $row->_total_blog ?></span>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>


