<?php
	$mr = [];

	$mr['withdraw'] = function() use ($withdraw)
	{
		$invoice_order = $withdraw->invoice_order;

		$body = macro()->info([
			lang('id')               => $invoice_order->id,
			lang('type')             => $invoice_order->service_name,
			lang('purse_number')     => $withdraw->purse->number,
			lang('withdraw_amount')  => $withdraw->{'format:amount'},
			lang('fee')              => $withdraw->{'format:fee'},
			lang('withdraw_payment') => $withdraw->payment->name,
			lang('receive_amount')   => "<b class='text-danger'>{$withdraw->{'format:receive_amount'}}</b>",
			lang('order_status')     => macro()->status_color($withdraw->status, lang('order_status_'.$withdraw->status)),
			lang('created')          => $withdraw->{'format:created,full'},
		]);

		return macro('mr::box')->box([
			'title'   => lang('title_invoice_order_view'),
			'content' => $body,
		]);
	};

	$mr['receiver'] = function() use ($receiver)
	{
		return macro('mr::box')->box([
			'title'   => lang('title_withdraw_receiver'),
			'content' => is_array($receiver) ? macro()->info($receiver) : $receiver,
		]);
	};

	echo $mr['withdraw']();

	echo $mr['receiver']();
