<style>
#withdraw_content .form-horizontal .form-group .col-sm-3{
	width:40%;
}
#withdraw_content .form-horizontal .form-group .col-sm-9{
	width:60%;
}
</style>
<div id="withdraw_content">
<?php
	$mr = [];

	$mr['input'] = function() use ($form)
	{
		$result = '';

		foreach (['purse_number', 'amount', 'payment_id'] as $param)
		{
			$result .= t('html')->hidden($param, $form->{$param});
		}

		foreach ($form->receiver_info as $row)
		{
			$result .= t('html')->hidden($row['param'], $row['value']);
		}

		return $result;
	};

	$mr['receiver'] = function() use ($receiver)
	{
		$result = '';

		foreach ($receiver as $name => $value)
		{
			$result .= macro('mr::form')->row([
				'param' => md5($name),
				'type'  => 'static',
				'name'  => $name,
				'value' => $value,
			]);
		}

		return $result;
	};
    
	echo macro('mr::form')->form([

		'title' => lang('title_withdraw_payment'),

		'rows' => [

			$mr['input'](),
            '<div class="col-sm-6">',
    			[
    				'param' => 'purse_number',
    				'type'  => 'static',
    				'value' => $form->purse->number,
    			],
    
    			[
    				'param' => 'purse_balance',
    				'type'  => 'static',
    				'value' => $form->purse->{'format:balance'},
    			],
    
    			[
    				'param' => 'amount',
    				'name'  => lang('withdraw_amount'),
    				'type'  => 'static',
    				'value' => $form->format('amount'),
    				'attr'  => ['class' => 'text-danger'],
    			],
    
    			[
    				'param' => 'fee',
    				'name'  => lang('fee'),
    				'type'  => 'static',
    				'value' => $form->format('fee'),
    			],
    
    			[
    				'param' => 'receive_amount',
    				'name'  => lang('amount_receive'),
    				'type'  => 'static',
    				'value' => $form->format('receive_amount'),
    				'attr'  => ['class' => 'text-danger'],
    			],
			'</div>',
			'<div class="col-sm-6">',
			
    			[
    			'param' => 'payment_id',
    			'name'   => lang('withdraw_payment'),
    			'type'  => 'static',
    			'value' => $form->payment->name,
    			],
    				
    			$mr['receiver'](),
			'</div>',
			
			mod('user_security')->form('withdraw'),

		],

	]);
?>
</div>