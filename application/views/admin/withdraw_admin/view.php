<?php
	$mr = [];

	$mr['withdraw'] = function() use ($withdraw)
	{
		$invoice_order = $withdraw->invoice_order;

		$body = macro()->info([
			lang('id')              => $invoice_order->id,
			lang('type')            => $invoice_order->service_name,
			lang('withdraw_admin')  => t('html')->a($withdraw->admin->{'adminUrl:view'}, $withdraw->admin->username, ['target' => '_blank']),
			lang('purse_number')    => $withdraw->purse->number,
			lang('withdraw_amount') => $withdraw->{'format:amount'},
			lang('withdraw_reason') => $withdraw->desc,
			lang('order_status')    => macro()->status_color($withdraw->status, lang('order_status_' . $withdraw->status)),
			lang('user')            => t('html')->a($withdraw->user->{'adminUrl:view'}, $withdraw->user->name, ['target' => '_blank']),
			lang('created')         => $withdraw->{'format:created,full'},
		]);

		return macro('mr::box')->box([
			'title'   => lang('title_invoice_order_view'),
			'content' => $body,
		]);
	};

	echo $mr['withdraw']();
