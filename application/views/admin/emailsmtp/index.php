<?php
	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	
	$_macro['table']['sort'] 	= false;
	$_macro['table']['sort_url_update'] = $sort_url_update;
	
	$_macro['table']['columns'] = array(
		'email'	=> lang('email'),
		'sendtotal'	=> lang('sendtotal'),
		'limit_per_day' 	=> lang('limit_per_day'),
			'default' 	=> lang('default'),
			'active' 	=> lang('active'),
		'action' 	=> lang('action'),
	);
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['active'] 	= macro()->status_color($row->_active);
		//$r['action'] 	= $_row_action($row);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);