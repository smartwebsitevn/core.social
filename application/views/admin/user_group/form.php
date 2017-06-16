<?php
	$mr = [];

	$mr['payments_data'] = function() use ($user_group)
	{
		$result = $user_group->payments;

		foreach ($result as &$row)
		{
			$row = ['status' => true];
		}

		return $result;
	};

	$mr['payments'] = function() use ($mr, $user_group, $payments, $currency)
	{
		ob_start(); ?>

		<div ng-init='payments = <?php echo json_encode($mr['payments_data']()) ?>'>

			<?php
				foreach ($payments as $row):

					$value = function($key) use ($user_group, $row)
					{
						return ($key == 'status')
							? isset($user_group->payments[$row->id])
							: array_get($user_group->payments, $row->id.'.'.$key);
					};

					$name = function($key) use ($row)
					{
						return "payments[{$row->id}][{$key}]";
					};

					$model = function($key) use ($row)
					{
						return "payments[{$row->id}].".$key;
					};
			?>

				<div class="input-group">

					<span class="input-group-addon">
						<label class="tcb">

							<?php echo t('html')->checkbox($name('status'), true, $value('status'), [
								'ng-model'   => $model('status'),
								'ng-checked' => "{$model('status')} == true",
								'class'      => 'tc',
							]); ?>

							<span class="labels"> <?php echo $row->name ?></span>

						</label>
					</span>

					<?php echo t('html')->input($name('amount_daily'), $value('amount_daily'), [
						'ng-disabled' => " ! {$model('status')}",
						'placeholder' => lang('payment_amount_daily')." ({$currency->code})",
						'class'       => 'form-control input_number',
					]); ?>

				</div>

			<?php endforeach; ?>

		</div>

		<?php return ob_get_clean();
	};

	$args = $this->data;

	$args['form'] = [

		'title' => $title,

		'data' => $user_group->toArray(),

		'attr' => ['ng-app' => ''],

		'rows' => [

			[
				'param' => 'name',
				'type'  => 'text',
			],

			[
				'param' => 'desc',
				'type'  => 'textarea',
			],

			/*[
				'param' => 'discount',
				'type'  => 'number',
				'unit'  => '%',
			],*/

			[
				'param' => 'balance_send_amount_daily',
				'type'  => 'number',
				'unit'  => $currency->code,
				'desc'  => lang('help_balance_send_amount_daily').'<br>'.lang('notice_option_apply'),
			],

			[
				'param' => 'payments',
				'type'  => 'custom',
				'name'  => lang('payments_allowed'),
				'html'  => $mr['payments'](),
				'desc'  => lang('help_payments'),
			],

			[
				'param'  => 'status',
				'type'   => 'bool',
				'value'  => array_get($user_group->toArray(), 'status', true),
				'values' => [lang('off'), lang('on')],
			],

		],

	];

	echo macro()->page($args);