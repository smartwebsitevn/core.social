<?php

$_macro = $this->data;

/* Tabs links */

$_macro['toolbar_sub'] = array(
	array('url' => admin_url('country'), 'title' => lang('country_info'), 'attr'=>array('class'=>'active') /* 'icon' => 'plus',*/),
    array('url' => admin_url('city'), 'title' => lang('city_info'), /*'icon' => 'list'*/),
);



/* Truyền dữ liệu cho form */

$info = isset($info) ? (array)$info : null;
$_macro['form']['data'] = $info;



/* Hotel name is required */
$_macro['form']['rows'][] = array(
	'param' => 'name',
	'placeholder' => lang('country_name'),
	'req' 	=> true,
);

$_macro['form']['rows'][] = array(
	'param' => 'group_id',
	'type' => 'select2',
	'name' => lang('region'),
	'values_row' => array( $regions, 'id', 'name' )
);


/* Short description type rich text box */

$_macro['form']['rows'][] = array(
	'param' => 'dial_code',
	'type' => 'spinner',
	'req' 	=> true,
);



/* Full description type rich text box */

$_macro['form']['rows'][] = array(
	'param' => 'code',
	'placeholder' => lang('country_code'),
	'req' 	=> true,
);

/* Hide this record */
$_macro['form']['rows'][] = array(
	'param' 	=> 'show',
	'name'		=> lang('status'),
	'type' 		=> 'bool_status',
	'value'		=> (isset($info['show']) ? $info['show'] : 1)
);


echo macro('mr::advForm')->page($_macro);