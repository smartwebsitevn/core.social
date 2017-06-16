<?php
	$table = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

	$table['title'] = lang('title_log_balance');

	$table['filters'] = [

		[
			'param' => 'user_key',
			'name'  => lang('user'),
			'value' => array_get($filter, 'user_key'),
			'attr' => ['title' => 'Có thể nhập id, username, email, số điện thoại của thành viên'],
		],

		[
			'param' => 'purse_key',
			'name'  => 'Số ví',
			'value' => array_get($filter, 'purse_key'),
		],

		[
			'param' => 'created',
			'type'  => 'date',
			'name'  => lang('from_date'),
			'value' => array_get($filter, 'created'),
		],

		[
			'param' => 'created_to',
			'type'  => 'date',
			'name'  => lang('to_date'),
			'value' => array_get($filter, 'created_to'),
		],

	];
	
	$table['columns'] = [
		'user_id'       => lang('user'),
		'purse_id'      => lang('purse'),
		'purse_amount'  => lang('amount'),
		'purse_balance' => lang('purse_balance'),
		'desc'          => lang('desc'),
		'created'       => lang('time'),
		'ip'       		=> 'IP',
		'url'       	=> 'Url',
		'action'        => lang('action'),
	];

	$table['rows'] = [];

	foreach ($list as $row)
	{
		$table['rows'][] = [
			'id'            => $row->id,
			'user_id'      	=> t('html')->a($row->user->{'adminUrl:view'}, $row->user->name, ['target' => '_blank']),
			'purse_id'      => $row->purse->number,
			'purse_amount'  => '<div class="text-right">'.$row->status.$row->{'format:purse_amount'}.'</div>',
			'purse_balance' => '<div class="text-right">'.$row->{'format:purse_balance'}.'</div>',
			'desc'          => implode('<br>', (array) $row->reason_desc),
			'created'       => $row->{'format:created,full'},
			'ip'       		=> $row->ip,
			'url'       	=> t('html')->a($row->url, 'Url', ['target' => '_blank']),
			'action'        => $row->{'url:detail'}
								? t('html')->a($row->{'adminUrl:detail'}, lang('button_detail'), ['class' => 'btn btn-primary btn-xs'])
								: null,
		];
	}

	echo macro()->page(array_merge($this->data, [
		'table'   => $table,
		'toolbar' => [],
	]));