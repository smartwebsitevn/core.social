<?php
	$mr = [];

	$mr['input'] = function() use ($form)
	{
		$result = '';

		foreach (['sender_purse_number', 'receiver_purse_number', 'amount', 'desc'] as $param)
		{
			$result .= t('html')->hidden($param, $form->{$param});
		}

		return $result;
	};

	echo macro('mr::form')->form([

		'title' => lang('title_transfer'),

		'rows' => [

			'<div name="transfer_error" class="alert alert-danger" style="display: none;"></div>',

			$mr['input'](),

//			[
//				'param' => 'sender_purse_number',
//				'name'  => lang('sender_purse'),
//				'type'  => 'static',
//				'value' => $form->sender_purse->number,
//			],

			[
				'param' => 'purse_balance',
				'type'  => 'static',
				'value' => $form->sender_purse->{'format:balance'},
			],

//			[
//				'param' => 'receiver_purse_number',
//				'name'  => lang('receiver_purse'),
//				'type'  => 'static',
//				'value' => $form->receiver_purse->number,
//			],

			[
				'param' => 'receiver',
				'type'  => 'static',
				'value' => $form->receiver->name,
			],

			[
				'param' => 'amount',
				'name'  => lang('transfer_amount'),
				'type'  => 'static',
				'value' => $form->format('amount'),
			],

			[
				'param' => 'fee',
				'name'  => lang('fee'),
				'type'  => 'static',
				'value' => $form->format('fee'),
			],

			[
				'param' => 'net',
				'name'  => lang('net'),
				'type'  => 'static',
				'value' => $form->format('net'),
				'attr'  => ['class' => 'text-danger'],
			],

			[
				'param' => 'desc',
				'name'  => lang('transfer_desc'),
				'type'  => 'static',
				'value' => nl2br($form->desc),
			],

			mod('user_security')->form('transfer'),

		],

	]);