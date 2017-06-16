<!-- Main content wrapper -->

<?php

$_macro = $this->data;

/* Tabs links */
/*$_macro['toolbar_sub'] = array(
    array('url' => admin_url('country'), 'title' => lang('country_info'), 'attr'=>array('class'=>'active') ),
    array('url' => admin_url('city'), 'title' => lang('city_info'),),
);*/



/* Khởi tạo bảng và nút xóa */
$_macro['table'] = array_only($this->data, array( 'total', 'actions', 'pages_config'));



/* Những trường có trong bộ lọc */
$_macro['table']['filters'] = array(
    array('name' => lang('country_name'), 'param' => 'name',
        'value' => $filter['name'],
    ),

    array('name' => lang('country_code'), 'param' => 'code',
        'value' => $filter['code'],
    ),

    array(
        'name' => lang('region'), 
        'param' => 'group_id',
        'type' => 'select2',
        'value' => $filter['group_id'],
        'values_row' => array( $regions, 'id', 'name' )
    ),

   array(
        'name' => lang('status'), 'type' => 'select', 'param' => 'status',
        'value' =>$filter['status'] ,
        'values' => array(
            '-1' => lang('all'),
            '1' => lang('show'), 
            '0' => lang('hide')
        ),
    ),

);



$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'name' => lang('country_name'),
    'dial_code' => lang('dial_code'),
    'code' => lang('code'),
    'group_id' => lang('region'),
    'status' => lang('status'),

    'action' => lang('action'),
);



$_rows = array();

foreach ($list as $row) {
    $status = $row->status ? 'on' : 'off';
    $r = (array)$row;
    $r['name'] 	= t('html')->a( 'javascript:;', $row->name, array('target'=>'_blank') );
    $r['group_id'] = $row->_group;
    $r['status']  = macro()->status_color($status) ;
    $r["status"] = '<a class="toggle_action iStar iIcon '.($row->_can_off ? 'on' : '').'"
								_url_on="'.$row->_url_on.'" _url_off="'.$row->_url_off.'"
								_title_on="'.lang('show').'" _title_off="'.lang('hide').'"
							></a>';
    $_rows[] = $r;
}

$_macro['table']['rows'] = $_rows;

echo macro('mr::advForm')->page($_macro);