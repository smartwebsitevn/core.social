<?php
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;
$_macro['form']['rows'][] = array(
    'param' => 'name','req'=>1
);
$_macro['form']['rows'][] = array(
    'param' => 'seo_url',
);
$_macro['form']['rows'][] = array(
    'param' => 'meta_title',
);
$_macro['form']['rows'][] = array(
    'param' => 'meta_key',
    'type' => 'textarea',
);
$_macro['form']['rows'][] = array(
    'param' => 'meta_desc',
    'type' => 'textarea',
);
$_macro['form']['rows'][] = array(
    'param' => 'feature',  'type' => 'bool',
    'value' => $info['feature'] ? 1 : 0
);
$_macro['form']['rows'][] = array(
    'param' => 'status', 'type' => 'bool_status',
    'value' => $info ? $info['status'] : 1,
);

echo macro()->page($_macro);