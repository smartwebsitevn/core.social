<?php

$_macro = $this->data;

/* Tabs links */

/*$_macro['toolbar_sub'] = array(
    array('url' => admin_url('country'), 'title' => lang('country_info')  ),
    array('url' => admin_url('city'), 'title' => lang('city_info'),  ),
    array('url' => admin_url('geo_zone'), 'title' => lang('geo_zone_info'), ),
    array('url' => admin_url('tax_rate'), 'title' => lang('tax_rate_info'), 'attr'=>array('class'=>'active'), ),
);*/



/* Truyền dữ liệu cho form */
$info = isset($info) ? (array)$info : null;

$_macro['form']['data'] = $info;

$_macro['form']['rows'][] = array(
	'param' => 'name',
	'placeholder' => lang('tax_rate_name'),
	'req' 	=> true,
);

$_macro['form']['rows'][] = array(
	'param' => 'rate',
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

$_macro['form']['rows'][] = array(
	'param' => 'type',
	'type' => 'select2',
	'req' 	=> true,
	'values' => $this->type
);
echo macro('mr::advForm')->page($_macro);

?>

