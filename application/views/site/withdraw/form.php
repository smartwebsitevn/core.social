<?php
	$mr = [];

	$mr['purses'] = function() use ($purses, $mr)
	{
		return $purses->map(function($row) use ($mr)
		{
			return [
				'number'        => $row->number,
				'balance'       => $row->{'format:balance'},
				'currency_code' => $row->currency->code,
			];
		});
	};

	$mr['receiver'] = function() use ($payments, $payments_form)
	{
		$result = '<div ng-switch on="payment_id" class="bank_withdraw">';

		foreach ($payments_form as $form)
		{
			$row = '';

			if ($form['form'])
			{
				$row = $form['form'];
			}
			else
			{
				foreach ($form['config'] as $config)
				{
					$row .= macro('mr::form')->row($config);
				}
			}

			$result .= '<div ng-switch-when="'.$form['payment_id'].'">'.$row.'</div>';
		}

		$result .= '</div>';

		return $result;
	};
?>

<script type="text/javascript" src="<?php echo public_url('modules/withdraw/form.js'); ?>"></script>
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var main = $('form#form_withdraw');
		
        var fee_content = $('.fee_content');
     
	    jQuery(document.body).on('change', '.bank_withdraw select',function()
        {
			var bank_withdraw = $(this).val();
			var purse_number  = $('select[name=purse_number]').val();
			
			$(this).nstUI(
	  				{
	    		    method:	"loadAjax",
		  			loadAjax:{
		  				url: '<?php echo site_url('withdraw/load_fee')?>',
		  				data: {'bank_withdraw': bank_withdraw, 'purse_number' : purse_number},
		  				field: {load: 'fee_content_load', show: ''},
		  				event_complete: function(data)
		  				{
			  				data = $.parseJSON(data);
		  					fee_content.slideDown(function(){ $(this).show(); });
		  					fee_content.html(data.fee_constant + ' + ' + data.fee_percent + '%');
		  				}
		  			},
		  	 });
			
		});
	});
})(jQuery);
</script>
<div id="form_withdraws"
	ng-module="WithdrawForm"
	ng-controller="FormController as form"
	ng-init='form.init(<?php echo json_encode([
		'purses' => $mr['purses'](),
	]); ?>)'
>

	<?php echo macro('mr::form')->form([

		'title' => lang('title_withdraw_payment'),

		'rows' => [

			'<div name="receiver_error" class="alert alert-danger" style="display: none;"></div>',
            '<div style="display:none">',
			[
				'param'  => 'purse_number',
				'type'   => 'select',
				'values' => $purses->lists('number', 'number'),
				'attr'   => [
					'ng-model' => 'form.purseNumber',
				],
			],
            '</div>',

		    [
    		    'param' => 'purse_number',
    		    'type'  => 'static',
    		    'value' => $user->username,
    		    'attr'  => ['class' => 'text-danger'],
		    ],
		    
			[
				'param' => 'purse_balance',
				'type'  => 'static',
				'value' => '{{ form.purse().balance }}',
				'attr'  => ['class' => 'text-danger'],
			],

			[
				'param' => 'amount',
				'name'  => lang('withdraw_amount'),
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
				'param'  => 'payment_id',
				'name'   => lang('withdraw_payment'),
				'type'   => 'select',
				'values' => $payments->lists('name', 'id'),
				'attr'   => [
					'ng-model' => 'payment_id',
					'ng-init'  => 'payment_id = "'.data_get($payments->first(), 'id').'"',
				],
			],

			$mr['receiver'](),
		    
            '<div style="position: relative;">
                <div id="fee_content_load" class="form_load"></div>',
		    [
    		    'param' => 'fee',
    		    'type'  => 'static',
    		    'value' => $fee_constant.' + '.$fee_percent.'%',
    		    'attr'  => ['class' => 'text-danger fee_content'],
		    ],
		    '</div>',
		    
			macro('mr::form')->captcha(),

		],

	]); ?>

</div>

<?php 
$withdraw_guide = setting_get('config-withdraw_guide');
if($withdraw_guide)
{
    echo macro('mr::box')->box([
        'title' => lang('withdraw_guide'),
        'body' => $withdraw_guide,
    ]);
}


$table = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

$table['title'] = lang('list_withdraw');
 
$table['columns'] = macro('tpl::invoice_order/macros')->make_columns();

$table['rows'] = macro('tpl::invoice_order/macros')->make_rows($orders);

echo macro('mr::table')->table($table);
?>