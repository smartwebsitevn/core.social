<?php
	$_row_status = function($status)
	{
		$lang = $status ? lang('action_unused') : lang('action_used');
		
		ob_start();?>

		<font class="status">
			<font class="<?php echo $status ? 'yes' : 'no'; ?>"
			><?php echo $lang; ?></font>
		</font>
		
		<?php return ob_get_clean();
	};
	
	
	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('orders', 'total', 'actions', 'pages_config'));


	$_macro['table']['filter']['data'] = $filter;
	$_macro['table']['filter']['rows'] = array(
	
		array(
			'param' => 'code',
			'name' => lang('card_code'),
		),
	
		array(
			'param' => 'serial',
			'name' => lang('card_serial'),
		),
	
		array(
			'param' 	=> 'amount',
			'type' 		=> 'select',
			'name' 		=> lang('card_amount'),
			'values' 	=> macro('mr::form')->make_options(array_combine($amounts, array_map('number_format', $amounts))),
		),
	
		array(
			'param' 	=> 'status',
			'type' 		=> 'select',
			'value' 	=> $filter['status'],
			'values' 	=> array('' => '', 'off' => lang('action_used'), 'on' => lang('action_unused')),
		),
	
	);
	
	
	$_macro['table']['columns'] = array(
		'code' 		=> lang('card_code'),
		'serial' 	=> lang('card_serial'),
		'amount'	=> lang('card_amount'),
		'expire'	=> lang('expire'),
		'status'	=> lang('status'),
		'action' 	=> lang('action'),
	);
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['amount'] 	= number_format($row->amount);
		$r['expire'] 	= $row->_expire;
		$r['status'] 	= $_row_status($row->status);
		$r['action'] 	= macro('mr::table')->action_row($row, ['edit']);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);