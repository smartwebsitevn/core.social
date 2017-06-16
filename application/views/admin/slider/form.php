<?php
	$_macro = $this->data;
	
	$_macro['form']['rows'][] = array(
		'param' => 'key',
		'req' 	=> true,
	);

	$_macro['form']['rows'][] = array(
		'param' => 'name',
		'req' 	=> true,
	);
	
	$_macro['form']['data'] = ( ! isset($info)) ? array() : (array) $info;
	
	echo macro()->page($_macro);