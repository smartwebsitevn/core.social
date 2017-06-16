<?php
	$invoice_order = $transfer->send_invoice_order;

	echo macro('mr::box')->box([

		'title' => lang('title_invoice_order_view'),

		'content' => macro()->info([
			lang('id')              => $invoice_order->id,
			lang('type')            => $invoice_order->service_name,
			lang('sender')          => t('html')->a($transfer->sender->{'adminUrl:view'}, $transfer->sender->name, ['target' => '_blank']),
			lang('sender_purse')    => $transfer->sender_purse->number,
			lang('receiver')        => t('html')->a($transfer->receiver->{'adminUrl:view'}, $transfer->receiver->name, ['target' => '_blank']),
			lang('receiver_purse')  => $transfer->receiver_purse->number,
			lang('transfer_amount') => $transfer->{'format:amount'},
			lang('fee')    			=> $transfer->{'format:fee'},
			lang('net')    			=> "<b class='text-danger'>{$transfer->{'format:net'}}</b>",
			lang('transfer_desc')   => $transfer->desc,
			lang('status')          => macro()->status_color($transfer->status, lang('tran_status_' . $transfer->status)),
			lang('created')         => $transfer->{'format:created,full'},
		]),

	]);
