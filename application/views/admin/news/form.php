<?php
$info = isset($info) ? (array)$info : null;
	$_macro = $this->data;
	$_macro['form']['data'] = $info;


   $_macro['form']['rows'][] = array(
		'param' => 'title',
		'req' 	=> true,
	);

$_macro['form']['rows'][] = array(
	'param' => 'cat_news_id','name'=>lang('cat'),'type'=>'select',
	'value'=>$info['cat_news_id'],'values_row'=>array($list_cat_news,'id','name'),
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
		'param' => 'intro',
        'type' => 'textarea',
		'req' 	=> true,
	);
$_macro['form']['rows'][] = array(
	'param' => 'content',
	'type' => 'html',
	'req' 	=> true,
);
/*$_macro['form']['rows'][] = array(
		'param' => 'tag','name'=>lang('tags'),
		'attr' => array('class'=>'tags form-control',
				'_url'=>admin_url('tag/getinfor'),
				),
);*/
$_macro['form']['rows'][] = array(
	'param' 	=> 'feature',
	'type' 		=> 'bool_status',
	'value'=>$info['feature']?1:0
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'status',
	'type' 		=> 'bool_status',
	'value'=>$info? $info['status']:1
);
/*$_macro['form']['rows'][] = array(
		'param' 	=> 'nofollow',
		'type' 		=> 'bool_status',
		'value'=>$info['nofollow']?1:0,
		'name_prefix' => 'nofollow_'
);
$_macro['form']['rows'][] = array(
		'param' 	=> 'noindex',
		'type' 		=> 'bool_status',
		'value'=>$info['noindex']?1:0,
		'name_prefix' => 'noindex_'
);
$_macro['form']['rows'][] = array(
		'param' 	=> 'comment_status',
		'type' 		=> 'bool',
		'value'=>(!isset($info['comment_status']) || $info['comment_status'])?1:0
);
$_macro['form']['rows'][] = array(
		'param' 	=> 'public',
		'type' 		=> 'bool_status',
		'value'=>(!isset($info['public']) || $info['public'])?1:0,
		'name_prefix' => 'public_'
);*/
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
$_macro['form']['rows'][] = array(
	'param' 	=> 'file',
	'type' 		=> 'file',
	'_upload' 	=> $widget_upload_files,
);
		echo macro()->page($_macro);