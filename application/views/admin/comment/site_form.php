<?php
$info= isset($info) ? (array) $info : null;
$_macro = $this->data;

$_macro['form']['data'] =$info;

$_macro['form']['rows'][] = array(
	'param' => 'rate_five',
	'req' 	=> true,
);
$_macro['form']['rows'][] = array(
	'param' => 'rate_four',
	'req' 	=> true,
);
$_macro['form']['rows'][] = array(
	'param' => 'rate_three',
	'req' 	=> true,
);
$_macro['form']['rows'][] = array(
	'param' => 'rate_two',
	'req' 	=> true,
);
$_macro['form']['rows'][] = array(
	'param' => 'rate_one',
	'req' 	=> true,
);
echo macro()->page($_macro);