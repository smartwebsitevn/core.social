<?php
	$mr = [];

	$mr['stats'] = function() use ($list)
	{
		$first = $list->first();

		$last = $list->last();

		$total_amount = $list->whereLoose('status', '+')->sum('purse_amount') - $list->whereLoose('status', '-')->sum('purse_amount');

		return [
			lang('balance_begin') => $last ? $last->{'format:purse_balance_pre'} : 0,
			lang('total_transfer_amount') => $first ? currency_format_amount($total_amount, $first->currency_id) : 0,
			lang('balance_end') => $first ? $first->{'format:purse_balance'} : 0,
		];
	};


	$table = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

	$table['filter'] = [

		'data' => $filter,

		'rows' => [

			[
				'param'  => 'purse_id',
				'type'   => 'select',
				'name'   => lang('purse'),
				'values' => $user->purses->lists('number', 'id'),
			],

			[
				'param' => 'created',
				'type'  => 'date',
				'name'  => lang('from_date'),
			],

			[
				'param' => 'created_to',
				'type'  => 'date',
				'name'  => lang('to_date'),
			],

		],

	];
	
	$table['columns'] = [
		'purse_id'      => lang('purse'),
		'purse_amount'  => lang('amount'),
		'purse_balance' => lang('purse_balance'),
		'desc'          => lang('desc'),
		'created'       => lang('time'),
		'action'        => lang('action'),
	];

	$table['rows'] = [];

	foreach ($list as $row)
	{
		$table['rows'][] = [
			'id'            => $row->id,
			'purse_id'      => $row->purse->number,
			'purse_amount'  => '<div class="text-right">' . $row->status . $row->{'format:purse_amount'} . '</div>',
			'purse_balance' => '<div class="text-right">' . $row->{'format:purse_balance'} . '</div>',
			'desc'          => implode('<br>', (array) $row->reason_desc),
			'created'       => $row->{'format:created,full'},
			'action'        => $row->{'url:detail'}
								? t('html')->a($row->{'url:detail'}, lang('button_detail'), ['class' => 'btn btn-success btn-xs'])
								: null,
		];
	}

	$table['stats'] = $mr['stats']();

	echo macro('mr::box')->box([
		'title' => lang('title_log_balance'),
		'content' => macro('mr::table')->table($table),
	]);