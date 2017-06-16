<?php
	$items = [];

	foreach ($list as $row)
	{
		$detail = t('html')->a($row['url'], lang('button_detail'), [
			'class' => 'pull-right',
			'style' => 'font-weight: normal',
		]);

		$label = $row['name'].$detail;

		$items[$label] = $row['total'];
	}

	echo macro('mr::box')->box([
		'title'   => lang('title_stats_request'),
		'content' => macro()->info($items),
	]);