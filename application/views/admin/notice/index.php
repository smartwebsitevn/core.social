<!-- Main content wrapper -->
<?php
$_macro = $this->data;

/* Tabs links */
/*$_macro['toolbar_sub'] = array(
    array('url' => admin_url('notice'), 'title' => lang('mod_notice'),'attr'=>array('class'=>'active')),
    array('url' => admin_url('notice_cat'), 'title' => lang('mod_notice_cat'), )
);*/


/* Khởi tạo bảng và nút xóa */
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
$_macro['table']['sort'] = true;
$_macro['table']['sort_url_update'] = $sort_url_update;
/* Những trường có trong bộ lọc */
$_macro['table']['filters'] = array(

    array(
        'name' => lang('name'),
        'param' => 'name',
        'value' => $filter['name']
    ),
    array(
        'name' => lang('status'),
        'type' => 'select',
        'param' => 'status',
        'value' => isset($filter['status']) && $filter['status'] != '' ? $filter['status'] : '-1',
        'values' => array(
            '1' => lang('status'),
            '0' => lang('hide')
        ),
    ),

);


/* Các cột trong list */

$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'key' => lang('key'),
    'name' => lang('name'),
    'status' => lang('status'),
    'action' => lang('action'),
);


/* Biến đổi giá trị hiển thị List */


$_rows = array();
foreach ($list as $row) {
    $r = (array)$row;
    $r['status'] = macro()->status_color($row->_status);
    $_rows[] = $r;
}

$_macro['table']['rows'] = $_rows;
echo macro('mr::advForm')->page($_macro);



