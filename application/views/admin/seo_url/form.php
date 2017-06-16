<?php
	$_macro = $this->data;
	$_macro['form']['data'] = isset($info) ? (array) $info : array();
	
	$_macro['form']['rows'][] = array(
		'param' => 'url_original',
		'req' 	=> true,
	);
	
	$_macro['form']['rows'][] = array(
		'param' => 'url_seo',
		'desc' 	=> lang('note_url_seo'),
		'req' 	=> true,
	);
	
	/* $_macro['form']['rows'][] = array(
		'param' => 'url_base',
		'desc' 	=> lang('note_url_base'),
	); */
	
	echo macro()->page($_macro);