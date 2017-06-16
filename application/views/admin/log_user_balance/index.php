<?php
$_macro = $this->data;
$_macro['toolbar'] = array();
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['filters'] = array(

    array(
        'param' => 'user_email', 'name' => lang('user'),
        'value'=>$filter['user_email'],

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
    'user' => lang('user'),
    'change' => lang('change'),
    'amount' => lang('amount'),
    'balance' => lang('balance'),
   // 'ip' => 'IP',
    'created' => lang('created'),
);

$_rows = array();
foreach ($list as $row) {
    //pr($row->user,false);
    $user = '[User was deleted]';
    if (isset($row->user) && $row->user)
        $user = t('html')->a(admin_url('user') . "?user_id={$row->user_id}", $row->user->email, ['target' => 'target']);
    $r = (array)$row;
    $r['user'] = $user .'<br>'.$row->url;
    $r['change'] = "{$row->change}";
    $r['amount'] = "<span class='right'>{$row->_amount}</span>";
    $r['balance'] = "<span class='right'>
               <b> {$row->_balance}</b><br/>
                [{$row->_balance_before}]
</span>";
    $r['created'] = $row->_created_full;

    $_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);