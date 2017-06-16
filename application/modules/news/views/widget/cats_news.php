<?php if ($widget->setting['style'] == 'news') { ?>
	<?php
	$_body = function () use ($news) {
		ob_start(); ?>
		<div class="news-widget-st1 news-widget">
			<?php foreach ($news as $row) { ?>
				<div class="view-row">
					<div class="post-date">
						<span class="date"><?php echo mdate('%d', $row->created) ?></span>
						<span class="month">th.<?php echo mdate('%m', $row->created) ?></span>
					</div>
					<div class="title">
						<a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->title; ?>">
						<?php echo $row->title; ?> </a>
					</div>
				</div>
			<?php } ?>
		</div>

		<?php return ob_get_clean();
	};
	echo macro('mr::box')->box_widget([
		'title' => $widget->name,
		'body' => $_body(),
	]);

	?>
	<div class="clearfix"></div>
<?php } else { ?>

	<?php
	$_body = function () use ($news) {
		ob_start(); ?>
		<div class="news-widget-st2 news-widget">
			<ul>
				<?php foreach ($news as $i => $item): ?>
					<li>
						<a href="<?php echo $item->_url_view; ?>"><?php echo $item->title; ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php return ob_get_clean();
	};
	echo macro('mr::box')->box_widget([
		'title' => $widget->name,
		'body' => $_body(),
	]);

	?>
	<div class="clearfix"></div>

<?php } ?>
