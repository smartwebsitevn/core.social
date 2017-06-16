<?php
	$_macro = $this->data;
	$_macro['form']['data'] = isset($info) ? (array) $info : null;


   $_macro['form']['rows'][] = array(
		'param' => 'name',
		'req' 	=> true,
	);

	$_macro['form']['rows'][] = array(
		'param' => 'code',
		'req' 	=> true,
	);

    $_macro['form']['rows'][] = array(
		'param' => 'banner_width',
	);
$_macro['form']['rows'][] = array(
	'param' => 'banner_height',
);

/*	$_macro['form']['rows'][] = array(
		'param' 	=> 'status',
		'type' 		=> 'bool',
	);*/
		echo macro()->page($_macro);