<?php
	$mr = [];

	$mr['input'] = function() use ($form)
	{
		$result = '';

		foreach (['purse_number', 'amount', 'desc'] as $param)
		{
			$result .= t('html')->hidden($param, $form->{$param});
		}

		return $result;
	};

	$purse = $form->purse;
	$user = $form->purse->user;

	$args = $this->data;

	$args['toolbar'] = [];

	$args['form'] = [

		'title' => lang('title_deposit_admin'),

		'rows' => [

			$mr['input'](),

			[
				'param' => 'purse_number',
				'type'  => 'static',
				'value' => $purse->number,
			],

			[
				'param' => 'user',
				'type'  => 'static',
				'value' => t('html')->a($user->{'adminUrl:view'}, $user->name, ['target' => '_blank']),
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

			[
				'param' => 'desc',
				'name'  => lang('deposit_reason'),
				'type'  => 'static',
				'value' => $form->desc,
			],

			[
				'param' => 'password',
				'name'  => lang('password'),
				'type'  => 'password',
				'desc'  => 'Nhập mật khẩu đăng nhập admin',
			],

		],

	];

	echo macro()->page($args);