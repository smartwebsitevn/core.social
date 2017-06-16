
<?php
	$_status_options = function() use ($statuss)
	{
		$result = array();
		
		foreach ($statuss as $v)
		{
			$result[$v] = lang('sms_status_'.$v);
		}
		
		return $result;
	};
	
	$_macro = $this->data;
	$_macro['toolbar'] = array();
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	$_macro['table']['sort'] 	= false;
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'port',
	    'name' 		=> lang('port'),
	    'value' 	=> $filter['port'],
	);
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'message',
	    'name' 		=> lang('message'),
	    'value' 	=> $filter['message'],
	);
	/*
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'status',
	    'type' 		=> 'select',
	    'value' 	=> $filter['status'],
	    'values' 	=> $_status_options(),
	);
    */
	
	$_macro['table']['filters'][] = array('name' => lang('from_date'), 'param' => 'created', 'type' => 'date',
	    'value' => $filter['created']
	);
	
	$_macro['table']['filters'][] = array('name' => lang('to_date'), 'param' => 'created_to', 'type' => 'date',
	    'value' => $filter['created_to'],
	);
	
	$_macro['table']['columns'] = array(
		'id' 		=> lang('id'),
	    'sms_id'    => lang('sms_id'),
	    'phone'     => lang('phone'),
		'port' 	    => lang('port'),
		'message' 	=> lang('message'),
		//'status' 	=> lang('status'),
		'created'	=> lang('created'),
	    //'action' 	=> lang('action'),
	);
	
	
	$_rows = array();
	foreach ($list as $row)
	{
	    $row->can_view = true;
	    
		$r = (array) $row;
		//$r['status']	= macro()->status_color($row->_status);
		$r['created'] 	= $row->_created_full;
		
		//$r['action'] 	= macro('mr::table')->action_row($row, ['view']);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);
	