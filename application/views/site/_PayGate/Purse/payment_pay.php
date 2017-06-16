<?php

$purse = $purses->first();

echo macro('mr::form')->form([

	'action'     => $action,
	'title'      => lang('title_purse_payment'),
	'btn_submit' => lang('button_payment'),

	'rows' => [

		t('html')->hidden('purse_id', $purse->id),

		[
			'param' => 'purse_id',
			'name'  => lang('payment_purse'),
			'type'  => 'custom',
			'html'  => "<b class='text-primary'>{$purse->number}</b>",
		],

		[
			'param' => 'purse_balance',
			'type'  => 'custom',
			'html'  => "<b class='text-primary'>{$purse->{'format:balance'}}</b>",
		],

		[
			'param' => 'payment_amount',
			'type'  => 'custom',
			'html'  => "<b class='text-danger'>{$format_amount}</b>",
		],

		mod('user_security')->form('payment'),

		macro('mr::form')->captcha($captcha),

	],

]);
