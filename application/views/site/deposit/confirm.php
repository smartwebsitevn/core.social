<?php
	$mr = [];

	$mr['input'] = function() use ($form)
	{
		$result = '';

		foreach (['purse_number', 'amount'] as $param)
		{
			$result .= t('html')->hidden($param, $form->{$param});
		}

		return $result;
	};

	$purse = $form->purse;

	echo macro('mr::form')->form([

		'title' => lang('title_deposit_payment'),

		'btn_submit' => lang('button_payment'),

		'rows' => [

			$mr['input'](),

			[
				'param' => 'purse_number',
				'type'  => 'static',
				'value' => $purse->number,
			],

			[
				'param' => 'purse_balance',
				'type'  => 'static',
				'value' => $purse->{'format:balance'},
			],

			[
				'param' => 'amount',
				'name'  => lang('deposit_amount'),
				'type'  => 'static',
				'value' => $form->format('amount'),
				'attr'  => ['class' => 'text-danger'],
			],

		],

	]);