<?php
	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

	
	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'key',
		'value' 	=> $filter['key'],
		'attr' 		=> array('style' => 'width:200px;'),
	);
	
	
	$_macro['table']['columns'] = array(
		'url' 		=> lang('url'),
		'title' 	=> lang('title'),
		'action'		=> lang('action'),
	);
	
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['action'] = macro('tpl::_macros/table')->action_row($row);
		
		foreach (array('url') as $p)
		{
			$r[$p] = t('html')->a($row->$p, $row->$p, array('target' => '_blank'));
		}
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	
	echo macro()->page($_macro);