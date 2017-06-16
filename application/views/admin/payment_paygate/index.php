<?php

	$rows = [];
	foreach ($list_installed as $row)
	{
		$rows[] = [
			'id'      => $row->id,
			'name'    => $row->name,
			'desc'    => $row->desc,
			'version' => $row->version,
			'status'  => macro()->status_color($row->status ? 'on' : 'off'),
			'action'  => macro('mr::table')->actions_data($row, [
				'edit',
				'uninstall' => [
					'confirm' => true,
					'notice'  => lang('notice_confirm_action', ['action' => lang('button_uninstall')]),
				],
			]),
		];
	}

	$table_installed = macro('mr::table')->table([

		'title' => lang('title_paygate_list_installed'),

		'columns' => [
			'name'    => lang('name'),
			'desc'    => lang('desc'),
			'version' => lang('version'),
			'status'  => lang('status'),
			'action'  => lang('action'),
		],

		'rows' => $rows,

	]);


	$rows = [];
	foreach ($list_not_installed as $row)
	{
		$rows[] = [
			'name'    => $row->name,
			'desc'    => $row->desc,
			'version' => $row->version,
			'action'  => macro('mr::table')->actions_data($row, ['install']),
		];
	}

	$table_not_installed = macro('mr::table')->table([

		'title' => lang('title_paygate_list_not_installed'),

		'columns' => [
			'name'    => lang('name'),
			'desc'    => lang('desc'),
			'version' => lang('version'),
			'action'  => lang('action'),
		],

		'rows' => $rows,

	]);

	echo macro()->page([
		'toolbar'  => [],
		'contents' => $table_installed . $table_not_installed,
	]);

