<!-- Main content wrapper -->
<?php
$_macro = $this->data;

/* Tabs links */


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
        'name' => lang('feature'),
        'type' => 'select',
        'param' => 'is_feature',
        'value' => isset($filter['is_feature']) && $filter['is_feature'] != '' ? $filter['is_feature'] : '-1',
        'values' => array(
            '1' => lang('yes'),
            '0' => lang('no')
        ),
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
    'name' => lang('name'),
    'is_default' => '',
    'status' => lang('status'),
    'action' => lang('action'),
);


/* Biến đổi giá trị hiển thị List */


$_data_name = function ($row) {
    ob_start(); ?>

        <img src="<?php echo $row->image->url_thumb; ?>" height="40px" width="50px"/>
        <?php echo $row->name; ?>
    <?php return ob_get_clean();
};

$_row_feature = function ($row) {
    ob_start();
    ?>
    <a class="toggle_action iIcon iStar <?php if ($row->_can_feature_del) echo 'on'; ?>"
       _url_on="<?php echo $row->_url_feature; ?>" _url_off="<?php echo $row->_url_feature_del; ?>"
       _title_on="<?php echo lang('feature_set'); ?>" _title_off="<?php echo lang('feature_del'); ?>"
        ></a>
    <?php return ob_get_clean();
};


$_rows = array();
foreach ($list as $row) {
    $r = (array)$row;
    $is_default = ($row->is_default) ? 'on' : 'off';
    //$r['name']  = t('html')->a( 'javascript:;', $row->name, array('target'=>'_blank') );
    $r['name'] = $_data_name($row);
    //$r['is_feature'] = $_row_feature($row);
    $r['is_default']='';
    if($row->is_default)
        $r['is_default'] = macro()->status_color($is_default,lang('default'));
    $r['status'] = macro()->status_color($row->_status );
    $_rows[] = $r;
}

$_macro['table']['rows'] = $_rows;
echo macro('mr::advForm')->page($_macro);



