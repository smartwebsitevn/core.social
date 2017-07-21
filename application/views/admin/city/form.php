<?php

$_macro = $this->data;

/* Tabs links */

$_macro['toolbar_sub'] = array(
    array('url' => admin_url('country'), 'title' => lang('country_info') /* 'icon' => 'plus',*/),
    array('url' => admin_url('city'), 'title' => lang('city_info'), 'attr'=>array('class'=>'active'), /*'icon' => 'list'*/),
);



/* Truyền dữ liệu cho form */
$info = isset($info) ? (array)$info : null;

$_macro['form']['data'] = $info;



/* Hotel name is required */
$_macro['form']['rows'][] = array(
	'param' => 'name',
	'placeholder' => lang('city_name'),
	'req' 	=> true,
);

$_macro['form']['rows'][] = array(
	'param' => 'code',
	'type'=>'text',
	'placeholder' => lang('city_code'),
);

/* Select the country */
$_macro['form']['rows'][] = array(
	'param' => 'country_id',
	'name'=> lang('country_name'), 
	'type'=>'select2',
	'value'=> $info['country_id'], 
	'values_row' => array( $country, 'id', 'name' ),
	'req' 	=> true,
);

/* Hide this record */
$_macro['form']['rows'][] = array(
	'param' 	=> 'feature',
	'name'		=> lang('feature'),
	'type' 		=> 'bool_status',
	'value'		=> (isset($info['feature']) ? $info['feature'] : 0)
);

$_macro['form']['rows'][] = array(
	'param' 	=> 'status',
	'name'		=> lang('status'),
	'type' 		=> 'bool_status',
	'value'		=> (isset($info['status']) ? $info['status'] : 1)
);

echo macro('mr::advForm')->page($_macro);