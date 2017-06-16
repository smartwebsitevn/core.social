<?php
	$_macro = $this->data;
	$_macro['form']['data'] = isset($info) ? (array) $info : array();
	
	$_macro['form']['rows'][] = array(
		'param' => 'url',
		'desc' 	=> lang('note_url'),
		'req' 	=> true,
	);
	
	foreach (array('title', 'description', 'keywords') as $p)
	{
		$_macro['form']['rows'][] = array(
			'param' => $p,
			'desc' 	=> lang('note_param_old_value', $param_old_value),
		);
	}
	
	echo macro()->page($_macro);