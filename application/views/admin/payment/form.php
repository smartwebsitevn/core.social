<?php
	$mr = [];

	$mr['form_data'] = function() use ($payment)
	{
		$data = $payment->toArray();

		foreach (['key', 'name', 'desc'] as $key)
		{
			$data = array_add($data, $key, $payment->paygate->{$key});
		}

		return $data;
	};

	$mr['info'] = function() use ($payment, $currencies)
	{
		$rows = [

			macro('mr::form')->row_title(lang('tab_info')),

			'<div name="setting_error" class="alert alert-danger hideit" style="display: none;"></div>',

			[
				'param' => 'key',
				'type'  => 'text',
				'attr'  => ['readonly' => $payment->id ? 'readonly' : null],
			],

			[
				'param' => 'name',
				'type'  => 'text',
			],

			[
				'param' => 'desc',
				'type'  => 'textarea',
			],

			[
				'param'  => 'currency_id',
				'type'   => 'select',
				'name'   => lang('currency'),
				'values' => $currencies->lists('name', 'id'),
			],

			[
				'param'  => 'status',
				'type'   => 'bool',
				'value'  => array_get($payment->toArray(), 'status', true),
				'values' => [lang('off'), lang('on')],
			],

		];

		return $rows;
	};

	$mr['options'] = function() use ($payment, $payment_services)
	{
		$rows = [macro('mr::form')->row_title(lang('tab_options'))];

		$keys = [
			'fee_constant', 'fee_percent', 'fee_min', 'fee_max',
			'amount_min', 'amount_max',
		];

		foreach ($keys as $key)
		{
			$rows[] = [
				'param' => 'options['.$key.']',
				'type'  => 'number',
				'name'  => lang($key),
				'value' => array_get($payment->options, $key),
				'desc'  => in_array($key, ['fee_max', 'amount_max']) ? lang('notice_option_apply') : null,
			];
		}

		foreach ($payment_services as $service)
		{
			$rows[] = [
				'param' => 'options[can_'.$service.']',
				'type'  => 'bool',
				'name'  => lang('allowed_'.$service),
				'value' => array_get($payment->options, 'can_'.$service, true),
			];
		}

		return $rows;
	};

	$mr['setting'] = function() use ($setting_form, $setting_config)
	{
		if ( ! count($setting_config)) return [];

		return array_merge(
			[macro('mr::form')->row_title(lang('tab_setting'))],
			$setting_form ? [$setting_form] : $setting_config
		);
	};


	$args = $this->data;

	$args['form'] = [

		'title' => $title,

		'data' => $mr['form_data'](),

		'rows' => array_merge($mr['info'](), $mr['setting'](), $mr['options']()),

	];

	echo macro()->page($args);