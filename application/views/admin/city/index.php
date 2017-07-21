<!-- Main content wrapper -->

<?php

$_macro = $this->data;

/* Tabs links */
$_macro['toolbar_sub'] = array(
    array('url' => admin_url('country'), 'title' => lang('country_info') /* 'icon' => 'plus',*/),
    array('url' => admin_url('city'), 'title' => lang('city_info'), 'attr'=>array('class'=>'active'), /*'icon' => 'list'*/),
);



/* Khởi tạo bảng và nút xóa */
$_macro['table'] = array_only($this->data, array( 'total', 'actions', 'pages_config'));


$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $sort_url_update;

/* Những trường có trong bộ lọc */

$_macro['table']['filters'] = array(
    array('name' => lang('city_name'), 'param' => 'name',
        'value' => $filter['name'],
    ),

    array(
        'name' => lang('country_name'), 'param' => 'country_id', 'type' => 'select',
        'value' => $filter['country_id'],
        'values_row' => array($country, 'id', 'name'),
    ),
    array(
        'name' => lang('feature'), 'type' => 'select', 'param' => 'feature',
        'value' =>$filter['feature'] ,
        'values' => array(
            '-1' => lang('all'),
            '1' => lang('on'),
            '0' => lang('off')
        ),
    ),
     array(
        'name' => lang('show'), 'type' => 'select', 'param' => 'status',
        'value' => ( $filter['status'] ? $filter['status'] : '-1' ),
        'values' => array(
            '-1' => lang('all'),
            '1' => lang('show'), 
            '0' => lang('hide')
        ),
    ),
);



$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'code' => lang('city_code'),
    'name' => lang('city_name'),
    'country_id' => lang('country_name'),
    'feature' => lang('feature'),
    'status' => lang('status'),
    'action' => lang('action'),
);



$_rows = array();
foreach ($list as $row) {
    $status = $row->status ? 'on' : 'off';
    $r = (array)$row;
    $r['name'] 	= t('html')->a( $row->_url_view, $row->name, array('target'=>'_blank') );
    $r['country_id'] = $row->_country->name;

    $r["feature"] = '<a class="toggle_action iStar iIcon '.($row->_can_feature_del ? 'on' : '').'"
								_url_on="'.$row->_url_feature.'" _url_off="'.$row->_url_feature_del.'"
								_title_on="'.lang('show').'" _title_off="'.lang('hide').'"
							></a>';
    /*$r["status"] = '<a class="toggle_action iStar iIcon '.($row->_can_off ? 'on' : '').'"
								_url_on="'.$row->_url_on.'" _url_off="'.$row->_url_off.'"
								_title_on="'.lang('show').'" _title_off="'.lang('hide').'"
							></a>';*/
    $r['status']  = macro()->status_color($status) ;

    $_rows[] = $r;
}

$_macro['table']['rows'] = $_rows;

echo macro('mr::advForm')->page($_macro);