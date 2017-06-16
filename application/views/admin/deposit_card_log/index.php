
<?php
	$_status_options = function() use ($statuss)
	{
		$result = array();
		
		foreach ($statuss as $v)
		{
			$result[$v] = lang('status_'.$v);
		}
		
		return $result;
	};
	
    $_providers = array();
    foreach ($providers as $row)
    {
        $_providers[$row->id] = $row->name;
    }
    $_types = array();
    foreach ($types as $row)
    {
        $_types[$row->key] = $row->name;
    }
    
	$_macro = $this->data;
	$_macro['toolbar'] = array();
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'user_id',
	    'name' 		=> lang('user'),
	    'value' 	=> $filter['user_id'],
	);
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'code',
	    'name' 		=> lang('card_code'),
	    'value' 	=> $filter['code'],
	);
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'serial',
	    'name' 		=> lang('card_serial'),
	    'value' 	=> $filter['serial'],
	);
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'provider',
	    'type' 		=> 'select',
	    'value' 	=> $filter['provider'],
	    'values' 	=> $_providers,
	);
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'type',
	    'type' 		=> 'select',
	    'value' 	=> $filter['type'],
	    'values' 	=> $_types,
	);
	
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'status',
	    'type' 		=> 'select',
	    'value' 	=> $filter['status'],
	    'values' 	=> $_status_options(),
	);

	$_macro['table']['filters'][] = array('name' => lang('from_date'), 'param' => 'created', 'type' => 'date',
	    'value' => $filter['created']
	);
	
	$_macro['table']['filters'][] = array('name' => lang('to_date'), 'param' => 'created_to', 'type' => 'date',
	    'value' => $filter['created_to'],
	);
	
	$_macro['table']['columns'] = array(
		'id' 		=> lang('id'),
		'provider' 	=> lang('provider'),
		'type' 		=> lang('card_type'),
		'code' 		=> lang('card_code'),
		'serial' 	=> lang('card_serial'),
		'amount' 	=> lang('card_amount'),
		'status' 	=> lang('status'),
		'message' 	=> 'Kết quả',
	    'ip' 		=> lang('ip'),
		'user' 		=> lang('user'),
		'created'	=> lang('created'),
	);

	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['user'] 		= '<a href="'.admin_url('deposit_card_log').'?user_id='.$row->user_id.'">'.$row->user->email.'<br/>'.$row->user->phone.'</a>';
		$r['status']	= macro()->status_color($row->_status);
		$r['amount'] 	= $row->amount ? $row->_amount : '';
		$r['created'] 	= $row->_created_full;
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);
	
	