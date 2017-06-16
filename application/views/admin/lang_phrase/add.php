<?php
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;


$_macro['form']['rows'][] = array(
    'param' => 'key',
    'req' => true,
);

$_macro['form']['rows'][] = array(
    'param' => 'value',
    'type' => 'textarea',
    'req' => true,
);
echo macro()->page($_macro);