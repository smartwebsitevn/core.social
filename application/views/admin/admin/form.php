<?php
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;

if ($info['is_root'])

    $_macro['form']['rows'][] = array(
        'param' => 'username',
        'type' => 'static',

    );
else
    $_macro['form']['rows'][] = array(
        'param' => 'username',
        'req' => true,
    );

$_macro['form']['rows'][] = array(
    'param' => 'password', 'type' => 'password',
    'req' => true,
);
$_macro['form']['rows'][] = array(
    'param' => 'password_repeat', 'type' => 'password',
    'req' => true,
);

if ($info['is_root'])
    $_macro['form']['rows'][] = array(
        'param' => 'admin_group',
        'type' => 'static',
        'value' => 'Supper admin',

    );
else
    $_macro['form']['rows'][] = array(
        'param' => 'admin_group',
        'type' => 'select',
        'value' => $info['admin_group_id'],
        'values_row' => array($admin_group, 'id', 'name'),
        'req' => true,

    );
$_macro['form']['rows'][] = array(
    'param' => 'blocked',
    'type' => 'bool',
    'value' => $info['blocked'],
);
foreach (array('name', 'phone', 'email') as $f) {

    $_macro['form']['rows'][] = array(
        'param' => $f,
        'req' => true,
    );
}
foreach (array('yahoo', 'skype', 'birthday') as $f) {

    $_macro['form']['rows'][] = array(
        'param' => $f,
    );
}
foreach (array('address', 'desc') as $f) {

    $_macro['form']['rows'][] = array(
        'param' => $f, 'type' => 'textarea'
    );
}
echo macro()->page($_macro);