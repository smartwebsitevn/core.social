<!-- Main content wrapper -->

<?php

$_macro = $this->data;

/* Tabs links */
/*$_macro['toolbar_sub'] = array(
    array('url' => admin_url('country'), 'title' => lang('country_info') ),
    array('url' => admin_url('city'), 'title' => lang('city_info'), ),
    array('url' => admin_url('geo_zone'), 'title' => lang('geo_zone_info'), ),
    array('url' => admin_url('tax_rate'), 'title' => lang('tax_rate_info'), 'attr'=>array('class'=>'active'),),
);*/



/* Khởi tạo bảng và nút xóa */
$_macro['table'] = array_only($this->data, array( 'total', 'actions', 'pages_config'));



/* Những trường có trong bộ lọc */

$_macro['table']['filters'] = array(
    array('name' => lang('tax_rate_name'), 'param' => 'name',
        'value' => $filter['name'],
    ),
    array('name' => lang('geo_zone_id'), 'param' => 'geo_zone_id',
        'value' => $filter['geo_zone_id'],
        'type' => 'select2',
        'values_row' => array( $geo_zone, 'id', 'name' )
    ),
    array('name' => lang('type'), 'param' => 'type',
        'value' => $filter['type'],
        'type' => 'select2',
        'values' => $this->type
    )
);



$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'name' => lang('tax_rate_name'),
    'rate' => lang('rate'),
    'type' => lang('type'),
    'geo_zone_id' => lang('geo_zone_id'),
    'created_date' => lang('created_date'),
    'modified_date' => lang('modified_date'),
    'action' => lang('action'),
);



$_rows = array();
foreach ($list as $row) {
    $r = (array)$row;
    $r['name'] 	= t('html')->a( $row->_url_view, $row->name, array('target'=>'_blank') );
    $r['type'] = $this->type[$row->type];
    $r['geo_zone_id'] = $row->_geo_zone_id->name;
    $r['created_date'] = date( 'd/m/Y', $row->created_date );
    $r['modified_date'] = date( 'd/m/Y', $row->modified_date );
    $_rows[] = $r;
}

$_macro['table']['rows'] = $_rows;

echo macro('mr::advForm')->page($_macro);