<?php
	$_macro = $this->data;
	$_macro['form']['data'] = isset($info) ? (array) $info : array();


   $_macro['form']['rows'][] = array(
		'param' => 'name',
		'req' 	=> true,
	);

	$_macro['form']['rows'][] = array(
		'param' 	=> 'status',
		'type' 		=> 'bool',
		'value' 	=> true,
	);
$_macro['form']['rows'][] = array(
		'param' => 'url',
);
$_macro['form']['rows'][] = array(
		'param' => 'titleweb',
);

$_macro['form']['rows'][] = array(
		'param' => 'description',
);
$_macro['form']['rows'][] = array(
		'param' => 'keywords',
);
	echo macro()->page($_macro);