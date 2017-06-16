<?php
$info= isset($info) ? (array) $info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;

$_macro['form']['rows'][] = array(
	'param' 	=> 'image',
	'type' 		=> 'image',
	'_upload' 	=> $widget_upload,
);
$_macro['form']['rows'][] = array(
	'param' => 'name',
	'req' 	=> true,
);

/*$_macro['form']['rows'][] = array(
	'param' => 'icon',
);*/
$_macro['form']['rows'][] = array(
	'param' => 'intro','type'=>'html'
);

$_macro['form']['rows'][] = array(
	'param' 	=> 'feature',
	'type' 		=> 'bool_status',
	'value'=>$info['feature']?$info['feature']:0,
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'status',
	'type' 		=> 'bool_status',
);

echo macro()->page($_macro);