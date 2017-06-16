<!-- Main content wrapper -->

<?php

$_macro = $this->data;

/* Tabs links */
/*$_macro['toolbar_sub'] = array(
    array('url' => admin_url('country'), 'title' => lang('country_info') ),
    array('url' => admin_url('city'), 'title' => lang('city_info'), ),
    array('url' => admin_url('geo_zone'), 'title' => lang('geo_zone_info'), 'attr'=>array('class'=>'active'),),
);
*/


/* Khởi tạo bảng và nút xóa */
$_macro['table'] = array_only($this->data, array( 'total', 'actions', 'pages_config'));



/* Những trường có trong bộ lọc */

$_macro['table']['filters'] = array(
    array('name' => lang('geo_zone_name'), 'param' => 'name',
        'value' => $filter['name'],
    )
);



$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'name' => lang('geo_zone_name'),
    'description' => lang('geo_zone_description'),
    'action' => lang('action'),
);



$_rows = array();
foreach ($list as $row) {
    $r = (array)$row;
    $r['name'] 	= t('html')->a( $row->_url_view, $row->name, array('target'=>'_blank') );
    $_rows[] = $r;
}

$_macro['table']['rows'] = $_rows;

echo macro('mr::advForm')->page($_macro);