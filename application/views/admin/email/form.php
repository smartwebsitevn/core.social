<?php
$_macro = $this->data;

$_macro['toolbar'] = array();
$_macro['form']['data'] = isset($info) ? (array) $info : array();
$_macro['form']['rows'][] = array(
	'param' => 'key',
	'type' 	=> 'static',
);
$_macro['form']['rows'][] = array(
	'param' => 'title',
	'req' 	=> true,
);
$_macro['form']['rows'][] = array(
	'param' => 'content',
	'type' => 'html',
	'req' 	=> true,
);

echo macro()->page($_macro);