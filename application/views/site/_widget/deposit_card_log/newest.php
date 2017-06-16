<?php
	$table = array_only($this->data, ['total', 'actions', 'pages_config']);

	$table['title'] = lang('deposit_card_log_title');
	$table['total'] = $total;
	$table['pages_config'] = $pages_config;
	
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
    	    'amount' 	=> currency_format_amount_default($row->amount),
    	    'created'	=> get_date($row->created, 'full'),
		];
	}
	
	echo macro('mr::table')->table($table);
	