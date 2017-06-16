<?php
	$mr = [];

	$mr['deposit'] = function() use ($deposit)
	{
		$invoice_order = $deposit->invoice_order;

		$body = macro()->info([
			lang('id')             => $invoice_order->id,
			lang('type')           => $invoice_order->service_name,
			lang('deposit_admin')  => t('html')->a($deposit->admin->{'adminUrl:view'}, $deposit->admin->username, ['target' => '_blank']),
			lang('purse_number')   => $deposit->purse->number,
			lang('deposit_amount') => $deposit->{'format:amount'},
			lang('deposit_reason') => $deposit->desc,
			lang('order_status')   => macro()->status_color($deposit->status, lang('order_status_' . $deposit->status)),
			lang('user')           => t('html')->a($deposit->user->{'adminUrl:view'}, $deposit->user->name, ['target' => '_blank']),
			lang('created')        => $deposit->{'format:created,full'},
		]);

		return macro('mr::box')->box([
			'title'   => lang('title_invoice_order_view'),
			'content' => $body,
		]);
	};

	echo $mr['deposit']();
