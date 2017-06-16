<style>
.column_action{
	width:20%;
}
</style>
<?php

	$rows = [];
	foreach ($list_installed as $row)
	{
		$rows[] = [
			'name'    => $row->name,
			'action'  => macro('mr::table')->actions_data($row, [
				'uninstall' => [
					'confirm' => true,
					'notice'  => lang('notice_confirm_action', ['action' => lang('button_uninstall')]),
				],
			    'setting', 
			    'set_default',
			    'test' => ['class' => 'lightbox']
			]),
		];
	}

	$table_installed = macro('mr::table')->table([

		'title' => lang('title_list_install'),
		'columns' => [
			'name'    => lang('name'),
			'action'  => lang('action'),
		],
		'rows' => $rows,

	]);


	$rows = [];
	foreach ($list_uninstall as $row)
	{
		$rows[] = [
			'name'    => $row->name,
			'action'  => macro('mr::table')->actions_data($row, ['install']),
		];
	}

	$table_not_installed = macro('mr::table')->table([

		'title' => lang('title_list_uninstall'),

		'columns' => [
			'name'    => lang('name'),
			'action'  => lang('action'),
		],

		'rows' => $rows,

	]);

	echo macro()->page([
		'toolbar'  => [],
		'contents' => $table_installed . $table_not_installed,
	]);
?>
