<?php
	if (t('input')->get('view_full'))
	{
		pr($list);
	}

	$_macro = $this->data;
	$_macro['toolbar'] = array();
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	

	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'key',
		'value' 	=> $filter['key'],
	);

	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'value',
		'value' 	=> $filter['value'],
	);
	
	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'created',
		'type' 		=> 'date',
		'name' 		=> lang('from_date'),
		'value' 	=> $filter['created'],
	);
	
	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'created_to',
		'type' 		=> 'date',
		'name' 		=> lang('to_date'),
		'value' 	=> $filter['created_to'],
	);
	
	
	$_macro['table']['columns'] = array(
		'key' 		=> lang('key'),
		'input'		=> 'Input',
		'output'	=> 'Output',
	);
	
	
	$_make_code = function($input)
	{
		ob_start();
		
		echo '<pre>';
		print_r($input);
		echo '</pre>';
		
		return ob_get_clean();
	};
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['key'] 		= $row->key.'<br>'.$row->_created_full;
		$r['input'] 	= $_make_code($row->input);
		$r['output'] 	= $_make_code($row->output);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);