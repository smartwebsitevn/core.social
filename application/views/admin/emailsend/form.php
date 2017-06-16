<?php
$_macro = $this->data;
$_macro['form']['data'] = isset($info) ? (array) $info : array();

$_macro['form']['rows'][] = array(
		'param' => 'usergroup','name'=>lang('mod_user_group'), 'type'=>'select_multi',
		'values_row'=> array($usergroup, 'id','name'),
);
$_macro['form']['rows'][] = array(
		'param' => 'emaillist','name'=>lang('email'),
		'type' => 'textarea',
		'desc' => lang('email_list_desc')
);

$_macro['form']['rows'][] = array(
		'param' => 'title','name'=>lang('title'),
		'type' => 'text',
		'req' 	=> true,
);
$_macro['form']['rows'][] = array(
		'param' => 'content',
		'type' => 'html',
		'req' 	=> true,
);

echo macro()->page($_macro);