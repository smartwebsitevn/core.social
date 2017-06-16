<?php

/**
 * Toolbar
 */
$this->register('toolbar', function()
{
	$items = [
		[
			'url'   => admin_url('stock_card/import'),
			'title' => 'Import file',
		],
		[
			'url'   => admin_url('stock_card/import_text'),
			'title' => 'Import text',
		],
		[
			'url'   => admin_url('stock_card'),
			'title' => 'Danh sách',
		],
	];

	$url_active = url_get_parent(array_pluck($items, 'url'));

	foreach ($items as &$item)
	{
		if ($item['url'] == $url_active)
		{
		    $item['attr']['class'] = 'btn btn-danger';
		}
	}

	return $items;
});
