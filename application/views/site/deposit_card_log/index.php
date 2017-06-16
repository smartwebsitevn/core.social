<?php
	$table = array_only($this->data, ['total', 'actions', 'pages_config', 'orders']);

	$table['title'] = lang('deposit_card_log_title');

	$table['filter'] = [

		'data' => $filter,

		'rows' => [
			[
				'param' => 'id',
			],
		    [
		        'param' => 'code', 'name' =>  lang('card_code')
		    ],
		    [
		        'param' => 'serial', 'name' =>  lang('card_serial')
		    ],
		],

	];

	$table['columns'] = [
		'id' 		=> lang('id'),
		'type' 		=> lang('card_type'),
		'code' 		=> lang('card_code'),
		'serial' 	=> lang('card_serial'),
		'amount' 	=> lang('card_amount'),
		'created'	=> lang('created'),
	];

	$table['rows'] = [];
	foreach ($list as $row)
	{
		$table['rows'][] = [
			'id'        => $row->id,
    	    'type' 		=> $row->type,
    	    'code' 		=> $row->code,
    	    'serial' 	=> $row->serial,
    	    'amount' 	=> $row->amount ? $row->_amount : '',
    	    'created'	=> $row->_created_full,
		];
	}
	
	echo macro('mr::table')->table($table);
	