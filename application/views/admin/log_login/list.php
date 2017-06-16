<?php
$_macro = $this->data;
$_macro['toolbar'] = array(
    array(
        'url' 	=> admin_url('log_login/admin'),
        'title' =>lang('title_log_login_admin'),
        'attr'=>array('class'=>'btn btn-primary'),
        'icon' => 'user',
    ),
    array(
        'url' 	=> admin_url('log_login/user'),
        'title' => lang('title_log_login_user'),
        'attr'=>array('class'=>'btn btn-primary'),
        'icon' => 'group',
    ),
);

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['filters'] = array(
    array(
        'param' => 'acc', 'name' => lang($table . '_id'),
        'value'=>$filter['acc'],
    ),
    array(
        'param' => 'ip', 'name' => lang('ip'),
        'value'=>$filter['ip'],
    ),

    array(
        'param' => 'created', 'type' => 'date', 'name' => lang('from_date'),
        'value'=>$filter['created'],
    ),

    array(
        'param' => 'created_to', 'type' => 'date', 'name' => lang('to_date'),
        'value'=>$filter['created_to'],
    ),

);

$_macro['table']['columns'] = array(
    $table . '_id' => lang($table . '_id'),
    $table => lang($table),
    'ip' => 'IP',
    'time' => lang('time'),
);

$_rows = array();
foreach ($list as $row) {
    $_acc = '';
    if ($table == 'admin') {
        $_acc = $row->acc->username;
    } elseif ($table == 'user') {
        $_acc = $row->acc->email;
    }

    if (isset($row->user) && $row->user)
        $user = t('html')->a(admin_url('user') . "?user_id={$row->user}", $row->acc->email, ['target' => 'target']);

    $r = (array)$row;
    $r[$table . '_id'] = t('html')->a('', $row->table_id, ['_param' => 'acc', '_value' => $row->table_id, 'class' => 'view_of_field tipS', 'title' => lang('view_of_acc') . '<br>' . $_acc]);
    $r[$table] = t('html')->a($row->acc->_url_view, $_acc, ['target' => 'target']);
    $r['ip'] = t('html')->a('', $row->ip, ['_param' => 'ip', '_value' => $row->ip, 'class' => 'view_of_field tipS', 'title' => lang('view_of_acc') . '<br>' . $row->ip]);
    $r['time'] = t('html')->a('', $row->_created_time, ['_param' => 'created', '_value' => $row->_created, 'class' => 'view_of_field tipS', 'title' => lang('view_of_created') . '<br>' . $row->_created]);


    $_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);