<?php

$_macro = $this->data;

/* Tabs links */

$_macro['toolbar_sub'] = array(
    array('url' => admin_url('country'), 'title' => lang('country_info') /* 'icon' => 'plus',*/),
    array('url' => admin_url('city'), 'title' => lang('city_info'), /*'icon' => 'list'*/),
    array('url' => admin_url('geo_zone'), 'title' => lang('geo_zone_info'), /*'icon' => 'list'*/),
    array('url' => admin_url('tax_rate'), 'title' => lang('tax_rate_info'), /*'icon' => 'list'*/),
    array('url' => admin_url('shipping_rate'), 'title' => lang('shipping_rate_info'), 'attr'=>array('class'=>'active'), /*'icon' => 'list'*/),
);



/* Truyền dữ liệu cho form */
$info = isset($info) ? (array)$info : null;

$_macro['form']['data'] = $info;

$_macro['form']['rows'][] = array(
	'param' => 'name',
	'placeholder' => lang('shipping_rate_name'),
	'req' 	=> true,
);

$_macro['form']['rows'][] = array(
	'param' => 'cost',
	'placeholder' => '0.000000',
	'type' => 'number',
	'req' 	=> true,
);

$_macro['form']['rows'][] = array(
	'param' => 'geo_zone_id',
	'type' => 'select2',
	'req' 	=> true,
	'values_row' => array( $geo_zone, 'id', 'name' )
);

/* Hide this record */
$_macro['form']['rows'][] = array(
	'param' 	=> 'show',
	'name'		=> lang('status'),
	'type' 		=> 'bool_status',
	'value'		=> (isset($info['show']) ? $info['show'] : 1)
);

/* Hide this record */
$_macro['form']['rows'][] = array(
	'param' 	=> 'sort',
	'name'		=> lang('sort'),
	'type' 		=> 'spinner',
	'value'		=> $info['sort']
);
echo macro('mr::advForm')->page($_macro);

?>

