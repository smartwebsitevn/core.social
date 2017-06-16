<?php
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['toolbar'] = array(
    array('url' => admin_url('cat') . '?type=' . $type, 'title' => lang('add'), 'icon' => 'plus', 'attr' => array('class' => 'btn btn-danger')),
    array('url' => admin_url('cat') . '?type=' . $type, 'title' => lang('list'), 'icon' => 'list', 'attr' => array('class' => 'btn btn-primary')),
);
$_macro['form']['data'] = $info;
if (in_array($type, mod('cat')->config('cat_hiarachy_types')))
    $_macro['form']['rows'][] = array(
        'param' => 'parent_id', 'name' => lang('parent_cat'), 'type' => 'select',
        'values_row' => array($parents, 'id', 'name'),
    );
$_macro['form']['rows'][] = array(
    'param' => 'name',
    'req' => true,
);
$_macro['form']['rows'][] = array(
    'param' => 'intro',
    'type' => 'html',
);
/*$_macro['form']['rows'][] = array(
	'param' => 'icon',
);*/

if (in_array($type, mod('cat')->config('cat_image_types')))
    $_macro['form']['rows'][] = array(
        'param' => 'image',
        'type' => 'image',
        '_upload' => $widget_upload,
    );


if (in_array($type, mod('cat')->config('cat_feature_types')))
    $_macro['form']['rows'][] = array(
        'param' => 'feature',
        'type' => 'bool_status',
        'value' => $info['feature'] ? $info['feature'] : 0,
    );


$_macro['form']['rows'][] = array(
    'param' => 'status',
    'type' => 'bool_status',
    'value' => $info? $info['status'] : 1,
);

/*$_macro['form']['rows'][] = array(
    'type' 	=> 'ob',
    'value' 	=> view('tpl::cat/translate',$this->data,true),
);*/

echo macro()->page($_macro);
?>
