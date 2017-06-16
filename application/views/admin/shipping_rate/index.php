<!-- Main content wrapper -->

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



/* Khởi tạo bảng và nút xóa */
$_macro['table'] = array_only($this->data, array( 'total', 'actions', 'pages_config'));



/* Những trường có trong bộ lọc */

$_macro['table']['filters'] = array(
    array('name' => lang('shipping_rate_name'), 'param' => 'name',
        'value' => $filter['name'],
    ),
    array('name' => lang('geo_zone_id'), 'param' => 'geo_zone_id',
        'value' => $filter['geo_zone_id'],
        'type' => 'select2',
        'values_row' => array( $geo_zone, 'id', 'name' )
    ),
    array(
        'name' => lang('show'), 'type' => 'select', 'param' => 'show',
        'value' => ( $filter['show'] ? $filter['show'] : '-1' ),
        'values' => array(
            '-1' => lang('all'),
            '1' => lang('show'), 
            '0' => lang('hide')
        ),
    ),
);



$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'name' => lang('shipping_rate_name'),
    'cost' => lang('cost'),
    'geo_zone_id' => lang('geo_zone_id'),
    'show' => lang('status'),
    'action' => lang('action'),
);



$_rows = array();
foreach ($list as $row) {
    $show = $row->show ? 'on' : 'off';
    $r = (array)$row;
    $r['name'] 	= t('html')->a( $row->_url_view, $row->name, array('target'=>'_blank') );
    $r['geo_zone_id'] = $row->_geo_zone_id->name;
    $r['show']  = macro()->status_color($show) ;
    $r['cost']  = dinhdangtien($row->cost);
    $_rows[] = $r;
}

$_macro['table']['rows'] = $_rows;

echo macro('mr::advForm')->page($_macro);