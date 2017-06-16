<?php

	$_macro = $this->data;
	$_macro['form']['data'] = isset($info) ? (array) $info : [];

	foreach ([
		'name', 'acc_id', 'acc_name', 'branch',
		'fee_constant', 'fee_percent', 'fee_min', 'fee_max', 'amount_min', 'amount_max',
	] as $p)
	{
		$type = 'text';
		$_desc = '';
		if (in_array($p, array('fee_min', 'fee_max', 'amount_min', 'amount_max')))
		{
			$type = 'number';
			$_desc = lang('notice_option_apply');
		}

		$_macro['form']['rows'][] = array(
			'param' => $p,
			'type'  => $type,
			'desc'  => $_desc,
			'req'   => in_array($p, array('name'/*, 'branch', 'acc_id', 'acc_name'*/)),
		);
	}
	foreach (['use_in_deposit', 'use_in_withdraw', /*'use_in_order',*/ 'status'] as $p)
	{
		$_macro['form']['rows'][] = array(
			'param' => $p,
			'type'  => 'bool_status',
		);
	}
	$_macro['form']['rows'][] = array(
		'param'   => 'image',
		'type'    => 'image',
		'_upload' => $widget_upload,
	);

	$_macro['form']['rows'][] = array(
		'param' => 'sort_order',
	);

	echo macro()->page($_macro);