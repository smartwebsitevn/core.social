<?php
	$args = $this->data;

	$args['toolbar'] = [];

	$args['table'] = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

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
	
    $args['table']['filters'][] = [
    	    'param' 	=> 'code',
    	    'name' 		=> lang('card_code'),
    	    'value' 	=> $filter['card_code'],
    ];
    
    $args['table']['filters'][] = array(
        'param' 	=> 'serial',
        'name' 		=> lang('card_serial'),
        'value' 	=> $filter['card_serial'],
    );
    $args['table']['filters'][] = array(
      'param' 	=> 'provider',
        'type' 		=> 'select',
        'value' 	=> $filter['provider'],
        'values' 	=> $_providers,
    	);
    $args['table']['filters'][] = array(
        'param' 	=> 'type',
        'type' 		=> 'select',
        'value' 	=> $filter['card_type_id'],
       'values' 	=> $_types,
    	);
    //
    $args['table']['filters'][] = array(
		'param' => 'user_key',
		'name'  => lang('user'),
		'value' => array_get($filter, 'user_key'),
		'attr' => ['title' => 'Có thể nhập username, email, số điện thoại của thành viên'],
    	);
    //
    $args['table']['filters'][] = array('name' => lang('from_date'), 'param' => 'created', 'type' => 'date',
        'value' => $filter['created']
    );
    //
    $args['table']['filters'][] = array('name' => lang('to_date'), 'param' => 'created_to', 'type' => 'date',
        'value' => $filter['created_to'],
    );
	
	$args['table']['columns'] = [
		'id'             => lang('id'),
		'provider'       => lang('provider'),
		'card_type'      => lang('card_type'),
		'card_code'      => lang('card_code'),
		'card_serial'    => lang('card_serial'),
		'card_amount'    => lang('card_amount'),
		'amount' 		=> lang('deposit_amount'),
		'profit'         => lang('profit'),
		'user_id'     => lang('user'),
		'created'        => lang('created'),
	];

	$rows = [];
	foreach ($list as $row)
	{
		$rows[] = [
			'id'          => $row->id,
			'provider'    => $row->provider,
			'card_type'   => $row->card_type_key,
			'card_code'   => $row->card_code,
			'card_serial' => $row->card_serial,
			'card_amount' => $row->{'format:card_amount'},
			'amount'      => $row->{'format:amount'},
			'profit'      => $row->{'format:profit_amount'},
			'user_id'     => '<a href="'.admin_url('deposit_card').'?user_id='.$row->user_id.'">'.$row->user->email.'<br/>'.$row->user->phone.'</a>',
			'created'     => $row->{'format:created,time'},
		];
	}

	$args['table']['rows'] = $rows;

	$args['table']['stats'] = [
		lang('total_card_amount') => $amount,
		lang('total_deposit_amount') => $amount_discount,
		lang('total_profit_amount') => $profit_amount,
	];

	echo macro()->page($args);
	