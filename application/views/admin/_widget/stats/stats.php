<?php
	$items = [];
	foreach ($list as $type => $amount)
	{
		$items[lang($type)] = $amount;
	}

	echo macro('mr::box')->box([
		'title'   => lang('title_stats'),
		'content' => macro()->info($items),
	]);