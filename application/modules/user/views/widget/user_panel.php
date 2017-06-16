<?php

if ($is_login):

	$make_menu = function() use ($menu_items)
	{
		ob_start();?>

		<div class="list-group">

			<?php foreach ($menu_items as $i => $item): ?>

				<a href="<?php echo $item->url; ?>"
				   class="list-group-item <?php if ($item->_is_active) echo 'active'; ?>"
					><?php echo $item->title; ?></a>

			<?php endforeach; ?>

		</div>

		<?php return ob_get_clean();
	};

	echo macro('mr::box')->box([
		'title'   => $widget->name,
		'content' => $make_menu(),
	]);

endif;
