<?php
	$items = [];

	foreach ($list as $type => $row)
	{
		$items[lang($type)] = $row->format('amount');
	}

	echo macro('mr::box')->box([
		'title'   => lang('title_invoice_stats_times'),
		'content' => macro()->info($items),
	]);