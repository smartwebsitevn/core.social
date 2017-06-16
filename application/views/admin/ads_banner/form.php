<?php
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;

$_macro['form']['rows'][] = array(
    'param' => 'ads_location','name'=>lang('location'), 'type' => 'select',
    'value' => $info['ads_location_id'], 'values_row' => array($locations, 'id', 'name'),
    'req' => true,
);
$_macro['form']['rows'][] = array(
    'param' => 'name',   'req' => true,
);

$_macro['form']['rows'][] = array(
    'param' => 'image_id', 'name' => lang('banner'), 'type' => 'image',
    '_upload' => $widget_upload,
    'req' => true,
);
$_macro['form']['rows'][] = array(
    'param' => 'url', 'name' => lang('banner_url'),
    'attr' => array('placeholder'=>site_url())
);
$_macro['form']['rows'][] = array(
    'param' => 'content',   'type' => "html",
);
$_macro['form']['rows'][] = array(
    'param' => 'end', 'name' => lang('date_expire'),'type'=>'date',
    'value' => (isset($info['end']) && $info['end'] > 0) ? $info['_end'] : '',
);
$_macro['form']['rows'][] = array(
    'param' => 'sort_order',
);

	$_macro['form']['rows'][] = array(
		'param' 	=> 'status',
		'type' 		=> 'bool_status',
		'value'=>(!isset($info->status) || $info->status)?1:0,
	);
/*$_macro['form']['rows'][] = array(
		'param' 	=> 'nofollow',
		'type' 		=> 'bool_status',
		'value'=>$info['nofollow']?1:0,
		'name_prefix' => 'nofollow_'
);*/
echo macro()->page($_macro);