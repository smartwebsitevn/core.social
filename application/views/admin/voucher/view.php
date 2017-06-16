<?php
$info = isset($info)?(array)$info:null;
$_macro = $this->data;
$_macro['form']['data'] =$info;
$_macro['form']['rows'][] = array(
    'param' => 'name',   'type' => 'static',
);
$_macro['form']['rows'][] = array(
    'param' => 'type',   'type' => 'static',
    'value'=>lang('voucher_type_'.$info['type'])
);
$_macro['form']['rows'][] = array(
    'param' => 'expired',  'type' => 'static',
    'value'=>get_date($info['expired'])
);
$s=$info['setting'];

if($info['type'] == 'vip'){
    $_macro['form']['rows'][] = array(
        'param' => 'time',   'type' => 'static',
        'value'=>$s->time
    );
}
elseif($info['type'] == 'coupon'){
    $_macro['form']['rows'][] = array(
        'param' => 'discount',   'type' => 'static',
        'value'=>$s->discount_type ==1?currency_format_amount($s->discount):$s->discount.'%',
    );

    $_macro['form']['rows'][] = array(
        'param' => 'discount_type',   'type' => 'static',
        'value'=>$s->discount_type ==1?'Cố định':'giảm theo % đơn hàng'
    );
}
$_macro['form']['rows'][] = array(
    'param' => 'comment',  'type' => 'static',
);
$_macro['form']['rows'][] = array(
    'param' => 'user_id','name'=>lang('apply_for_user'), 'type' => 'static',
);
$_macro['form']['rows'][] = array(
    'param' => 'admin_id','name'=>lang('apply_for_admin'), 'type' => 'static',
);
echo macro()->page($_macro);
?>