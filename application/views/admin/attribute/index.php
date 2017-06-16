<!-- Main content wrapper -->
<?php
$_macro = $this->data;

/* Tabs links */
$_macro['toolbar_sub'] = $this->_toolbar;



/* Edit & Delete Permission */
$_row_action = function($row)
{
    ob_start();?>
    
    <?php if ($row->_can_edit): ?>
        <a href="<?php echo $row->_url_edit; ?>" title="<?php echo lang('edit'); ?>" class="tipS"
        ><img src="<?php echo public_url('admin') ?>/images/icons/color/edit.png" /></a>
    <?php endif; ?>


    <?php if ($row->_can_del): ?>
        <a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action" 
            notice="<?php echo lang('notice_confirm_delete'); ?>:<br><b><?php echo $row->name; ?></b>"
        ><img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" /></a>
    <?php endif; ?>

    <?php return ob_get_clean();
};



/* Khởi tạo bảng và nút xóa */
$_macro['table'] = array_only($this->data, array( 'total', 'actions', 'pages_config'));

/* Những trường có trong bộ lọc */
$_macro['table']['filters'] = array(
    array(
        'param' => 'name',
        'value' => $filter['name'],
    ),

    array(
        'type' => 'select2',
        'param' => 'group_id',
        'value' => $filter['group_id'],
        'values_row' => array( $groups, 'id', 'name' )
    ),

    array(
        'name'      => lang('show'), 'type' => 'select', 'param' => 'show',
        'value'     => isset($filter['show']) && $filter['show'] != '' ? $filter['show'] : '-1',
        'values'    => array(
            '-1'    => lang('all'),
            '1'     => lang('show'), 
            '0'     => lang('hide')
        ),
    ),

);



/* Các cột trong list */

$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'group_id' => lang('group_id'),
    'name' => lang('name'),
    'sort' => lang('sort'),
    'show' => lang('status'),
    'action' => lang('action'),
);



/* Biến đổi giá trị hiển thị List */

$_rows = array();
foreach ($list as $row) {
    $show = $row->show ? 'on' : 'off';
    $r = (array)$row;
    $r['name']  = t('html')->a( '#', $row->name, array('target'=>'_blank') );

    $r['group_id'] = '';
    if(! empty($row->_group) )
        $r['group_id']  = $row->_group->name;

    $r['show']  = macro()->status_color($show) ;
    $_rows[] = $r;
}

$_macro['table']['rows'] = $_rows;
echo macro('mr::advForm')->page($_macro);



