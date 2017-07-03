<!-- Main content wrapper -->
<?php
$_macro = $this->data;

/* Khởi tạo bảng và nút xóa */
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
$_macro['toolbar'] = array();
/* Những trường có trong bộ lọc */
$_macro['table']['filters'] = array(
    array(
        'name' => lang('title'),
        'param' => 'title',
        'value' => $filter['title']
    ),
    array(
        'param' => 'readed',
        'name' 	=>  lang('user'), 'type'=> 'select',
        'value' => $filter['readed'],
        'values' => array(
            '1' => lang('read_yes'),
            '0' => lang('read_no')
        ),
    ),
    array(
        'param' => 'admin_readed',
        'name' 	=>  lang('admin'), 'type'=> 'select',
        'value' => $filter['admin_readed'],
        'values' => array(
            '1' => lang('read_yes'),
            '0' => lang('read_no')
        ),
    ),
/*
    array(
        'name' => lang('status'),
        'type' => 'select',
        'param' => 'status',
        'value' => isset($filter['status']) && $filter['status'] != '' ? $filter['status'] : '-1',
        'values' => array(
            '1' => lang('status'),
            '0' => lang('hide')
        ),
    ),*/

  /*  array('name' => lang('from_date'), 'param' => 'created', 'type' => 'date',
        'value' => $filter['created'],
    ),
    array('name' => lang('to_date'), 'param' => 'created_to', 'type' => 'date',
        'value' => $filter['created_to'],
    ),*/
);


/* Các cột trong list */

$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'title' => lang('title'),
    'readed'		=> lang('user'),
    'admin_readed'		=> lang('admin'),
    'created'	=> lang('created'),
    //'status' => lang('status'),
    'action' => lang('action'),
);


/* Biến đổi giá trị hiển thị List */


$_row_action =function($row){
    ob_start()
    ?>
    <?php if ($row->_can_view): ?>
        <a href="<?php echo $row->_url_view; ?>" title="<?php echo lang('detail'); ?>" class="tipS lightbox" data-width="70%" >
            <img src="<?php echo public_url('admin') ?>/images/icons/color/view.png" />
        </a>
    <?php endif; ?>

    <?php if ($row->_can_del): ?>
        <a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action"
           notice="<?php echo lang('notice_confirm_delete'); ?>:<br><b><?php echo htmlentities($row->title); ?></b>"
            >
            <img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" />
        </a>
    <?php endif;
    ?>
    <?php return ob_get_clean();
};
$_rows = array();
foreach ($list as $row) {
    $readed_status = ($row->readed) ? 'on' : 'off';
    $readed_text = ($row->readed) ? 'read_yes' : 'read_no';

    $admin_readed_status = ($row->admin_readed) ? 'on' : 'off';
    $admin_readed_text = ($row->admin_readed) ? 'read_yes' : 'read_no';

    $r = (array)$row;
    $r['readed'] = macro()->status_color($readed_status,$readed_text) ;
    $r['admin_readed'] = macro()->status_color($admin_readed_status,$admin_readed_text) ;
    $r['created'] 	= $row->_created_full;
    $r['action'] 	= $_row_action($row);

    $_rows[] = $r;
}

$_macro['table']['rows'] = $_rows;
echo macro('mr::advForm')->page($_macro);



