<?php
	$table = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

	$table['title'] = lang('title_tran_list');

	$table['filter'] = [

		'data' => $filter,

		'rows' => [

			[
				'param' => 'id',
			],

		],

	];

	$table['columns'] = [
		'id'              => lang('id'),
		'amount'          => lang('amount'),
		'status'          => lang('status'),
		'payment_id'      => lang('payment'),
		'payment_tran_id' => lang('payment_tran_id'),
		'created'         => lang('created'),
		'action'          => lang('action'),
	];

	$table['rows'] = [];

	foreach ($list as $row)
	{
		$table['rows'][] = [
			'id'              => $row->id,
			'amount'          => $row->{'format:amount'},
			'status'          => macro()->status_color($row->status, lang('tran_status_' . $row->status)),
			'payment_id'      => $row->payment_id ? $row->payment->name : '',
			'payment_tran_id' => $row->payment_tran_id,
			'created'         => $row->{'format:created,time'},
			'action'          => t('html')->a($row->{'url:view'}, lang('button_view'), ['class' => 'btn btn-success btn-xs']),
		];
	}
	
	echo macro('mr::table')->table($table);