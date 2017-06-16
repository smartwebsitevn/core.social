<!-- Main content wrapper -->
<?php

$_macro = $this->data;
$_macro['toolbar_sub'] = array(
	array('url' => admin_url('news'), 'title' => lang('mod_news') ,/* 'icon' => 'plus',*/),
	array('url' => admin_url('news_cat'), 'title' => lang('mod_news_cat'),'attr'=>array('class'=>'active') /*'icon' => 'list'*/)
);
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $sort_url_update;

$_macro['table']['filters'] = array(
	array(  'name' 	=> lang('id'),'param' => 'id',
		'value' 	=>$filter['id'],
	),
	array(  'name' 	=> lang('name'),'param' => 'name',
		'value' 	=>$filter['name'],
	),
	array(
		'name' 	=>  lang('status'), 'type'=> 'select',	'param' => 'status',
		'value' => $filter['status'],
		'values' =>array('on'=>lang('active'),'off'=>lang('unactive')),
	),

);

$_macro['table']['columns'] = array(
	'id' 		=> lang('id'),
	'name'		=> lang('name'),
	'status'	=> lang('status'),
	'lang'		=> lang('lang'),
		'admin' => lang('admin'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$r = (array) $row;
	$r['name'] 	= '<a target="_blank" href="'.$row->_url_view.'" >'.$row->name.'</a>';
	$r['status'] 	= ($row->status) ? lang('on') : lang('off');
	$r['lang'] 	= macro('mr::table')->langs_url($row);
	$r['admin'] = (isset($row->admin->name) ? $row->admin->name : '').'<p style="font-size: 11px">('.$row->_updated_time.')</p>';
	//$r['action'] 	= $_row_action($row);

	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

