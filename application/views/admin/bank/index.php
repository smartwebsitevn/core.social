<?php


	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	
	$_macro['table']['sort'] 	= true;
	$_macro['table']['sort_url_update'] = $sort_url_update;
	
	$_macro['table']['columns'] = array(
		'name' 		=> lang('name'),
		'acc_id'	=> lang('acc_id'),
		'acc_name'	=> lang('acc_name'),
	    'fee_constant' => lang('fee_constant'),
	    'fee_percent' => lang('fee_percent'),
		'status'	=> lang('status'),
		'action' 	=> lang('action'),
	);
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['status'] 	= macro()->status_color($row->_status);
		$r['fee_constant'] 	= currency_format_amount($row->fee_constant);
		$r['fee_percent'] 	= $row->fee_percent.'%';
		
		//$r['action'] 	= $_row_action($row);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);