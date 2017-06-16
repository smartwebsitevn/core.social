
<?php if (count($list)): ?>

<div class="t-box">
	<div class="box-title">
		<h6>
			<?php echo lang('title_news_feature'); ?>
		</h6>
	</div>
	<div class="box-content">
	
	<?php $news_top = $list[0]; ?>
	<div class="latest-post-blog m0">
		<a href="<?php echo $news_top->_url_view; ?>" title="<?php echo $news_top->title; ?>">
			<img width="60" height="50" src="<?php echo $news_top->image->url_thumb; ?>" class="attachment-small-thumb wp-post-image" alt="<?php echo $news_top->title; ?>" title="<?php echo $news_top->title; ?>">
		</a>
		<p><a class="link" href="<?php echo $news_top->_url_view; ?>" title="<?php echo $news_top->title; ?>">
			<?php echo $news_top->title; ?>
		</a></p>
	</div>
	<div class="clear"></div>
	
	<?php unset($list[0]); ?>
	<ul class="list-style small f11 m0">
		<?php foreach ($list as $row): ?>
			<li class="arrow-list" style="padding:2px 0 2px 20px; background-position: 5px 7px; ">
				<a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->title; ?>">
					<?php echo $row->title; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	</div>
</div>
	
<?php endif; ?>