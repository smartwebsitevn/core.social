<?php
$_macro = $this->data;
$_macro['form']['data'] = isset($info) ? (array) $info : array();

foreach (array(
			 'name','regency',/* 'phone',  'address',*/

		 ) as $p)
{
	$_macro['form']['rows'][] = array(
		'param' 	=> $p,
		'req' 		=> true,
	);
}
$_macro['form']['rows'][] = array(
	'param' 	=> 'say',
	'type' 		=> 'html',
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'image',
	'type' 		=> 'image',
	'_upload' 	=> $widget_upload,
);
$_macro['form']['rows'][] = array(
	'param' => 'sort_order',
);
$_macro['form']['rows'][] = array(
	'param' => 'status',
	'type' => 'bool_status',
);
echo macro()->page($_macro);