<!-- Main content wrapper -->
<?php

$_macro = $this->data;

$_macro['toolbar_sub'] = array(
	array('url' => admin_url('faq'), 'title' => lang('mod_faq')),
	array('url' => admin_url('faq_cat'), 'title' => lang('mod_faq_cat'),'attr'=>array('class'=>'active') )
);
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $sort_url_update;

/*$_macro['table']['filters'] = array(
	array(  'name' 	=> lang('id'),'param' => 'id',
		'value' 	=>$filter['id'],
	),
	array(  'name' 	=> lang('name'),'param' => 'name',
		'value' 	=>$filter['name'],
	),

);*/

$_macro['table']['columns'] = array(
	'id' 		=> lang('id'),
	'name'		=> lang('name'),
	'status'		=> lang('status'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$status = ($row->status) ? 'on' : 'off';
	$r = (array) $row;
	$r['status'] 	= macro()->status_color($status) ;

	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

