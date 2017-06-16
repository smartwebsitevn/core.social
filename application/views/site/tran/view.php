<?php

echo macro('mr::box')->box([
	'title'   => lang('title_tran_view'),
	'content' => macro()->info([
		lang('id')              => $tran->id,
		lang('amount')          => $tran->{'format:amount'},
		lang('status')          => macro()->status_color($tran->status, lang('tran_status_'.$tran->status)),
		lang('payment')         => $tran->payment_id ? $tran->payment->name : '',
		lang('payment_tran_id') => $tran->payment_tran_id,
		lang('created')         => $tran->{'format:created,full'},
	]),
]);