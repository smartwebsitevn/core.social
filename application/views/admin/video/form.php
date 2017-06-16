<?php
$info = isset($info) ? (array)$info : null;
	$_macro = $this->data;

	$_macro['form']['data'] = $info;

$_macro['form']['rows'][] = array(
		'param' => 'lang_id',
		'name' => lang('mod_lang'),
		'req' 	=> true,
		'type' => 'select',
		'values_row'=> array(lang_get_list(),'id','name'),
);
   $_macro['form']['rows'][] = array(
		'param' => 'name',
		'req' 	=> true,
	);

$_macro['form']['rows'][] = array(
		'param' 	=> 'created',
		'type' 	=> 'date',
		'req' 	=> true,
		'value'=>$info['created']?get_date($info['created']):get_date(),
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'image',
	'type' 		=> 'image',
	'_upload' 	=> $widget_upload,
);
	$_macro['form']['rows'][] = array(
		'param' => 'summary',
        'type' => 'textarea',
	);

$_macro['form']['rows'][] = array(
	'param' => 'content',
	'type' => 'html',
//	'req' 	=> true,
);
$_macro['form']['rows'][] = array(
		'param' => 'video',
		'type' => 'textarea',
		'desc' => lang('video_desc')
);
$_macro['form']['rows'][] = array(
		'param' => 'tag','name'=>lang('tags'),
		'attr' => array('class'=>'tags form-control','_url'=>admin_url('tag/getinfor')),
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'feature',
	'type' 		=> 'bool_status',
	'value'=>$info['feature']?1:0
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'status',
	'type' 		=> 'bool_status',
);
$_macro['form']['rows'][] = array(
		'param' 	=> 'nofollow',
		'type' 		=> 'bool_status',
		'value'=>$info['nofollow']?1:0,
		'name_prefix' => 'nofollow_'
);

$_macro['form']['rows'][] = array(
		'param' 	=> 'comment_status',
		'type' 		=> 'bool',
		'value'=>(!isset($info['comment_status']) || $info['comment_status'] == 1)?1:0
);
$_macro['form']['rows'][] = array(
		'param' 	=> 'public',
		'type' 		=> 'bool_status',
		'value'=>(!isset($info['public']) || $info['public'])?1:0,
		'name_prefix' => 'public_'
);
$_macro['form']['rows'][] = array(
		'param' => 'url',
);
$_macro['form']['rows'][] = array(
		'param' => 'titleweb',
);

$_macro['form']['rows'][] = array(
		'param' => 'description',
);
$_macro['form']['rows'][] = array(
		'param' => 'keywords',
);

		echo macro()->page($_macro);