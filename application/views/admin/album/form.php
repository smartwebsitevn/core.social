<?php
$info= isset($info) ? (array) $info : null;
$_macro = $this->data;

$_macro['form']['data'] =$info;

$_macro['form']['rows'][] = array(
	'param' => 'name',
	'req' 	=> true,
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'image',
	'type' 		=> 'image',
	'_upload' 	=> $widget_upload,
);
/*$_macro['form']['rows'][] = array(
	'param' 	=> 'file',
	'type' 		=> 'file',
	'name' => lang('images'),
	'_upload' 	=> $widget_upload_files,
);*/
$_macro['form']['rows'][] = array(
	'param' => 'content','type'=>'html'
);

/*$_macro['form']['rows'][] = array(
	'param' => 'icon',
);
*/

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
	'param' 	=> 'feature',
	'type' 		=> 'bool_status',
	'value'=>$info['feature']?1:0
);
$_macro['form']['rows'][] = array(
	'param' 	=> 'status',
	'type' 		=> 'bool_status',
);


echo macro()->page($_macro);