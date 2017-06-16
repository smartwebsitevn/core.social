<?php
//pr($cats);
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['toolbar_sub'] = array(
	array('url' => admin_url('faq'), 'title' => lang('mod_faq'),'attr'=>array('class'=>'active')),
	array('url' => admin_url('faq_cat'), 'title' => lang('mod_faq_cat'), )
);
$_macro['form']['data'] = $info;

$_macro['form']['rows'][] = array(
	'param' => 'cat_id','name'=>lang('cat'),'type'=>'select',
	'value'=>$info['cat_id'],'values_row'=>array($cats,'id','name'),
	'req' 	=> true,
);
$_macro['form']['rows'][] = array(
    'param' => 'question',
    'req' => true,
);


$_macro['form']['rows'][] = array(
    'param' => 'answer',
    'type' => 'html',
    'req' => true,
);

$_macro['form']['rows'][] = array(
    'param' => 'status',
    'type' => 'bool_status',
	'value'=>$info?$info['status']:1,
);

/*$_macro['form']['rows'][] = array(
	'param' 	=> 'image',
	'type' 		=> 'image',
	'_upload' 	=> $widget_upload,
);*/
echo macro()->page($_macro);