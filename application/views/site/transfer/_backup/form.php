<?php
	$mr = [];

	$mr['make_amount_limit'] = function($purse) use ($purses_setting)
	{
		$setting = $purses_setting[$purse->id];

		$result = [lang('min').': '.$setting['format_amount_min']];

		if ($setting['amount_max'])
		{
			$result[] = lang('max').': '.$setting['format_amount_max'];
		}

		return implode(' - ', $result);
	};

	$mr['purses'] = function() use ($purses, $mr)
	{
		return $purses->map(function($row) use ($mr)
		{
			return [
				'number'        => $row->number,
				'balance'       => $row->{'format:balance'},
				'currency_code' => $row->currency->code,
				'amount_limit'  => $mr['make_amount_limit']($row),
			];
		});
	};

?>

<script type="text/javascript" src="<?php echo public_url('modules/transfer/form.js'); ?>"></script>

<div
	ng-module="TransferForm"
	ng-controller="FormController as form"
	ng-init='form.init(<?php echo json_encode([
		'purses' => $mr['purses'](),
		'urlLoadName' => $url_load_name,
	]); ?>)'
>

	<?php echo macro('mr::form')->form([

		'title' => lang('title_transfer'),

		'rows' => [

			'<div name="transfer_error" class="alert alert-danger" style="display: none;"></div>',

			[
				'param'  => 'sender_purse_number',
				'name'   => lang('sender_purse'),
				'type'   => 'select',
				'values' => $purses->lists('number', 'number'),
				'attr'   => [
					'ng-model' => 'form.purseNumber',
				],
			],

			[
				'param' => 'purse_balance',
				'type'  => 'static',
				'value' => '{{ form.purse().balance }}',
				'attr'  => ['class' => 'text-danger'],
			],

			[
				'param' => 'receiver_purse_number',
				'name'   => lang('receiver_purse'),
				'type'  => 'custom',
				'html'  => '
					<div class="input-group">
						<input name="receiver_purse_number" class="form-control" type="text"
							ng-model="form.receiverPurse"
					   		ng-model-options="{updateOn: \'blur\'}"
							ng-change="form.loadReceiverName()"
						/>
						<span class="input-group-addon">{{ form.receiverName }}</span>
					</div>
				',
			],

			[
				'param' => 'amount',
				'name'  => lang('transfer_amount'),
				'type'  => 'custom',
				'html'  => '
					<div class="input-group">
						<input name="amount" class="form-control input_number" type="text" />
						<span class="input-group-addon">{{ form.purse().currency_code }}</span>
					</div>
				',
				'desc'  => '{{ form.purse().amount_limit }}',
			],

			[
				'param' => 'desc',
				'name'  => lang('transfer_desc'),
				'type'  => 'textarea',
			],

			macro('mr::form')->captcha(),

		],

	]); ?>

</div>
