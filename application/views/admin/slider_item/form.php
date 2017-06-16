<?php
	$_macro = $this->data;
	$_macro['form']['data'] = ( ! isset($info)) ? array() : (array) $info;
	$_macro['form']['rows'][] = array(
		'param' 	=> 'slider_id',
		'name' 		=> lang('slider'),
		'type' 		=> 'select',
		'values'	=> array_pluck($list_slider, 'name', 'id'),
		'req' 		=> true,
	);

	$_macro['form']['rows'][] = array(
		'param' 	=> 'image',
		'type' 		=> 'image',
		'_upload' 	=> $widget_upload,
		'req' 		=> true,
	);

	$_macro['form']['rows'][] = array(
	    'param' 	=> 'name'
	);
$_macro['form']['rows'][] = array(
	'param' 	=> 'target',
	'type'      => 'html'
);

$_macro['form']['rows'][] = array(
	    'param' 	=> 'desc',
	    'type'      => 'html'
	);
	
	
	$_macro['form']['rows'][] = array(
		'param' 	=> 'url',
	);
	$_macro['form']['rows'][] = array(
		'param' 	=> 'sort_order'
	);

	$_macro['form']['rows'][] = array(
		'param' => 'status','type' => 'bool_status',
	);

	echo macro()->page($_macro);