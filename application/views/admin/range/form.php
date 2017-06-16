<?php
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['toolbar'] = array(
	array('url' => admin_url('range').'?type='.$type, 'title' => lang('add'), 'icon' => 'plus','attr'=>array('class'=>'btn btn-danger')),
	array('url' => admin_url('range').'?type='.$type, 'title' => lang('list'), 'icon' => 'list','attr'=>array('class'=>'btn btn-primary')),
);
$_macro['form']['data'] =$info;
$_macro['form']['rows'][] = array(
	'param' => 'name',
	'req' 	=> true,
);

$_macro['form']['rows'][] = array(
	'param' 	=> 'from',
	'type' 		=> 'spinner',
);
if(!in_array($type,mod('range')->config('range_no_to_types')))
$_macro['form']['rows'][] = array(
	'param' 	=> 'to',
	'type' 		=> 'spinner',
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'status',
	'type' 		=> 'bool_status',
	'value' => $info? $info['status'] : 1,
);
echo macro()->page($_macro);