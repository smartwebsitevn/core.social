<?php
	$mr = [];

	$mr['deposit'] = function() use ($deposit)
	{
		$invoice_order = $deposit->invoice_order;

		$body = macro()->info([
			lang('id')             => $invoice_order->id,
			lang('type')           => $invoice_order->service_name,
			lang('card_type')      => $deposit->card_type->name,
			lang('card_code')      => $deposit->card_code,
			lang('card_serial')    => $deposit->card_serial,
			lang('card_amount')    => $deposit->{'format:card_amount'},
			lang('deposit_amount') => $deposit->{'format:amount'},
			lang('purse')          => $deposit->purse->number,
			lang('order_status')   => macro()->status_color($deposit->status, lang('order_status_'.$deposit->status)),
			lang('created')        => $deposit->{'format:created,full'},
		]);

		return macro('mr::box')->box([
			'title'   => lang('title_invoice_order_view'),
			'content' => $body,
		]);
	};

	echo $mr['deposit']();
