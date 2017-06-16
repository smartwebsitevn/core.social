<?php
	$items = [];

	foreach ($list as $row)
	{
		$items[$row->service_name] = $row->format('amount');
	}

	echo macro('mr::box')->box([
		'title'   => lang('title_invoice_stats_services_today'),
		'content' => macro()->info($items),
	]);