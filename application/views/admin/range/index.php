<!-- Main content wrapper -->
<?php

$_macro = $this->data;

$_macro['toolbar'] = array(
	array('url' => admin_url('range/add/'.$type), 'title' => lang('add'), 'icon' => 'plus','attr'=>array('class'=>'btn btn-danger')),
	array('url' => admin_url('range'), 'title' => lang('list'), 'icon' => 'list','attr'=>array('class'=>'btn btn-primary')),
);
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $sort_url_update;

$_macro['table']['filters'] = array(
	array( 'param' => 'type','type'=>'select',
		'value' => $filter['type'], 'values_single' => $types,'values_opts'=>array('value_required'=>1,'name_prefix'=>'range_')
	),

);

$_macro['table']['columns'] = array(
	'id' 		=> lang('id'),
	'name'		=> lang('name'),
	'status'	=> lang('status'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$v = ($row->status) ? 'on' : 'off';
	$r = (array) $row;
	$r['name'] 	= $row->name;
	$r['status'] 	= macro()->status_color($v) ;;
	//$r['action'] 	= $_row_action($row);

	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

