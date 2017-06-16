<?php

/**
 * Make columns
 */
$this->register('make_columns', function()
{
	return [
		'id'              => lang('id'),
		'amount'          => lang('amount'),
		'status'          => lang('status'),
		'payment_id'      => lang('payment'),
		'payment_tran_id' => lang('payment_tran_id'),
		'user_id'         => lang('customer'),
		'user_ip'         => 'IP',
		'created'         => lang('created'),
		'action'          => lang('action'),
	];
});

/**
 * Make rows
 */
$this->register('make_rows', function($list)
{
	$rows = [];

	foreach ($list as $row)
	{
		$rows[] = [
			'id'              => $row->id,
			'amount'          => $row->{'format:amount'},
			'status'          => macro()->status_color($row->status, lang('tran_status_' . $row->status)),
			'payment_id'      => $row->payment_id ? $row->payment->name : '',
			'payment_tran_id' => $row->payment_tran_id,
			'user_id'         => $row->user_id
				? implode('<br>', array_filter([$row->user->username, $row->user->email, $row->user->phone]))
				: lang('guest'),
			'user_ip'         => t('html')->img(public_url('img/world/'.strtolower($row->user_country_code) . '.gif')).' '.$row->user_ip,
			'created'         => $row->{'format:created,full'},
			'action'          => macro('mr::table')->actions_data($row, ['view']),
		];
	}

	return $rows;
});
