<?php


$_macro = $this->data;
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['toolbar_addon']  =array(
    array(
        'url' 	=> $url_export,'title' => lang('button_export'),'icon' => 'plus',
        'attr'=>array('class'=>'btn btn-primary response_action',
            'notice'=>lang('notice_verify_export'),
            '_url' =>$url_export,
        ),
    ),

);



$_macro['table']['filters'] = array(
    array(
        'name' => lang('key'),  'param' => 'key',
        'value' => $filter['key'],
    ),
    array(
        'name' => lang('type'), 'type' => 'select', 'param' => 'type',
        'value' => $filter['type'],
        'values_single' => $types, 'values_opts' => ['name_prefix' => 'voucher_type_'],
    ),
    array(
        'name' => lang('status'), 'type' => 'select', 'param' => 'status',
        'value' => $filter['status'],
        'values' => array('used' => lang('status_used'), 'not_used' => lang('status_not_used')),
    ),
    array(
        'name' => lang('expired'), 'type' => 'select', 'param' => 'expired',
        'value' => $filter['expired'],
        'values' => array('expired' => lang('status_expired'), 'not_expired' => lang('status_not_expired')),
    ),
    array(
        'name' => lang('commission_for'),  'param' => 'commission',
        'value' => $filter['commission'],
    ),
);
$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'type' => lang('type'),
    'key' => lang('key'),
    'status' => lang('status'),
    'created' =>lang('expired'),
    //'user_id' =>lang('commission'),
    'action' => lang('action'),
);


$_data_type = function ($row) {
    ob_start(); ?>
    <b><?php echo lang('voucher_type_' . $row->type); ?></b><br>
    <?php
    if(isset($row->_user))
        echo 'Hoa hồng thành viên:'.$row->_user->email.'<br>';
    if(isset($row->_admin))
        echo 'Hoa hồng Admin:'.$row->_admin->username.'<br>';
    ?>
    <?php echo $row->comment; ?>
    <?php return ob_get_clean();
};

$_data_status = function ($row) {
    ob_start(); ?>
    <?php
    echo macro()->status_color($row->status ? 'no' : 'yes', $row->_status);
    if($row->status)

    ?>
        <?php if($row->status): ?>
        <i><?php echo get_date($row->used_time) ?></i>
        <?php endif; ?>
    <?php return ob_get_clean();
};
$r['status'] =

$_data_created = function ($row) {
    ob_start(); ?>
    <?php
    //echo $row->_expired.'<br>('.$row->_day_left.')<br>';
    // echo $row->_created;
    ?>
    <?php if ($row->_day_left > 0 ): ?>
        <label class="label label-yes">Còn <?php echo $row->_day_left_format ?></label>
    <?php else: ?>
        <label class="label label-danger ">Hết hạn</label>
    <?php endif; ?>
<br>
    <i><?php echo $row->_expired ?></i>

    <?php return ob_get_clean();
};
$_rows = array();
foreach ($list as $row) {
    //$s = $row->status ? 'status_yes' : 'status_no';
    $r = (array)$row;
    $r['type'] = $_data_type($row);
    $r['key'] = '<b class="red f17">'.$row->key.'</b>';
    $r['status'] = $_data_status($row);
    $r['created'] = $_data_created($row);
    //$r['action'] 	= $_row_action($row);
    $_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);