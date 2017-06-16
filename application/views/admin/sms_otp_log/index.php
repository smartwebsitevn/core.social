
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
	
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'phone',
	    'name' 		=> lang('phone'),
	    'value' 	=> $filter['phone'],
	);
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'message',
	    'name' 		=> lang('message'),
	    'value' 	=> $filter['message'],
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
	    'mod' 		=> lang('mod'),
	    'type' 		=> lang('type'),
		'phone' 	=> lang('phone'),
		'message' 	=> lang('message'),
		'status' 	=> lang('status'),
		'created'	=> lang('created'),
	);
	
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['status']	= macro()->status_color($row->_status);
		$r['created'] 	= $row->_created_full;
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);
	