<?php
	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

	
	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'key',
		'value' 	=> $filter['key'],
		'attr' 		=> array('style' => 'width:200px;'),
	);
	
	
	$_macro['table']['columns'] = array(
		'url_original' 	=> lang('url_original'),
		'url_seo' 		=> lang('url_seo'),
		'url_base'		=> lang('url_base'),
		'action'		=> lang('action'),
	);
	
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['action'] = macro('tpl::_macros/table')->action_row($row);
		
		foreach (array('url_original', 'url_seo', 'url_base') as $p)
		{
			$r[$p] = t('html')->a($row->{'_'.$p}, $row->$p, array('target' => '_blank'));
		}
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	
	echo macro()->page($_macro);