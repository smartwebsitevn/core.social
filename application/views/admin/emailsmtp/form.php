<?php
$_macro = $this->data;
$_macro['form']['data'] = isset($info) ? (array) $info : array();

$_macro['form']['rows'][] = array(
		'param' => 'type','name'=>lang('type'), 'type'=>'select',
		'values_single'=> $emailsmtp,
		'values_opts' => array('name_prefix' => 'emailsmtp_'),
		'req' 		=> true,
);
foreach (array(
				 'host','email'

		 ) as $p)
{
	$_macro['form']['rows'][] = array(
		'param' 	=> $p,
		'req' 		=> true,
	);
}
$_macro['form']['rows'][] = array(
	'param' 	=> 'password',
		'req' 		=> true,
);
$_macro['form']['rows'][] = array(
	'param' => 'port',
		'value' => isset($info->port) ? $info->port : 21,
);
$_macro['form']['rows'][] = array(
		'param' => 'timeout',
);
$_macro['form']['rows'][] = array(
		'param' => 'limit_per_day',
		'value' => isset($info->limit_per_day) ? $info->limit_per_day : 500,
);
$_macro['form']['rows'][] = array(
		'param' => 'limit_per_send',
		'value' => isset($info->limit_per_send) ? $info->limit_per_send : 90,
);
$_macro['form']['rows'][] = array(
		'param' => 'limit_delay',
		'value' => isset($info->limit_delay) ? $info->limit_delay : 5,
);
$_macro['form']['rows'][] = array(
	'param' => 'active',
	'type' => 'bool_status',
);
echo macro()->page($_macro);