<?php
$info=isset($info) ? (array) $info :null;
	$_macro = $this->data;
	$_macro['toolbar'] = array(
		array('url' => admin_url('form_content').'?type='.$type, 'title' => lang('add'), 'icon' => 'plus','attr'=>array('class'=>'btn btn-danger')),
		array('url' => admin_url('form_content').'?type='.$type, 'title' => lang('list'), 'icon' => 'list','attr'=>array('class'=>'btn btn-primary')),
	);
	$_macro['form']['data'] =$info;
  /* $_macro['form']['rows'][] = array(
		'param' => 'title',
		'req' 	=> true,
	);
	$_macro['form']['rows'][] = array(
		'param' => 'content','type'=>'textarea'
	);*/


	$_macro['form']['rows'][] = array(
		'param' 	=> 'status',
		'type' 		=> 'bool_status',
		'value'=>$info?$info['status']:1,

	);

	$_macro['form']['rows'][] = array(
		'type' 	=> 'ob',
		'value' 	=> view('tpl::form_content/translate',$this->data,true),
	);
	echo macro()->page($_macro);
?>
