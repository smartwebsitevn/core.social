<?php
	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

	$_macro['table']['sort'] = true;
	$_macro['table']['sort_url_update'] = $sort_url_update;

	
	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'name',
		'value' 	=> $filter['name'],
	);
	
	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'provider',
		'type' 		=> 'select',
		'value' 	=> $filter['provider'],
		'values' 	=> array_pluck($providers, 'name', 'id'),
	);
	
	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'status',
		'type' 		=> 'select',
		'value' 	=> $filter['status'],
		'values' 	=> array('' => '', 'off' => lang('off'), 'on' => lang('on')),
	);
	
	
	$_macro['table']['columns'] = array(
		'name' 		=> lang('name'),
		'key' 		=> lang('key'),
		'provider'	=> lang('provider'),
		'fee'		=> lang('fee').' (%)',
	    //'profit'		=> lang('profit').' (%)',
		'status'	=> lang('status'),
		'action' 	=> lang('action'),
	);
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['status'] = macro()->status_color($row->_status);
		$r['action'] = macro('tpl::_macros/table')->action_sort() . macro('tpl::_macros/table')->action_row($row);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);
	
	