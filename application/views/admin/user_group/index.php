<?php
//pr($list);
	$rows = [];
	foreach ($list as $row)
	{
		$rows[] = [
			'id'      => $row->id,
			'name'    => $row->name,
			'desc'    => $row->desc,
			'status'  => macro()->status_color($row->status ? 'on' : 'off'),
			'action'  => macro('mr::table')->action_sort() . macro('mr::table')->actions_data($row, [
				'edit',
				'delete' => [
					'confirm' => true,
					'notice'  => lang('notice_confirm_action', ['action' => lang('button_delete')]),
				],
			]),
		];
	}

	$table = macro('mr::table')->table([

		'title' => lang('title_user_group_list'),

		'sort' => true,
		'sort_url_update' => $sort_url_update,

		'columns' => [
			'name'    => lang('name'),
			'desc'    => lang('desc'),
			'status'  => lang('status'),
			'action'  => lang('action'),
		],

		'rows' => $rows,

	]);

	echo macro()->page([
		'contents' => $table,
	]);

