<?php
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;


$_macro['form']['rows'][] = array(
	'param' 	=> 'key','type' =>($info)?'static':'text',
	'req' => true,
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'name','req' 		=> 1,
);

$_macro['form']['rows'][] = array(
	'param' 	=> 'sort_order'
);

echo macro()->page($_macro);