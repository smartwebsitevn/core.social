<?php
$info= isset($info) ? (array) $info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;
$_macro['form']['rows'][] = array(
	'param' => 'name',
	'req' 	=> true,
);
$_macro['form']['rows'][] = array(
	'param' => 'status',
	'type' => 'bool_status',
	'value'=>$info?$info['status']:1,
);
echo macro()->page($_macro);