<!-- Main content wrapper -->
<?php

$_macro = $this->data;
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

//$_macro['table']['sort'] 	= true;
//$_macro['table']['sort_url_update'] = $sort_url_update;
$_macro['toolbar_addon']  =array(
	array(
		'url' 	=> $url_export,'title' => lang('button_export'),'icon' => 'plus',
		'attr'=>array('class'=>'btn btn-primary response_action',
			'notice'=>lang('notice_confirm'),
			'_url' =>$url_export,
		),
	),

);
$_macro['table']['filters'] = array(
	array( 'param' => 'id',
		'value' 	=>$filter['id'],
	),
	array( 'param' => 'email',
		'value' 	=>$filter['email'],
	),

);
$_macro['table']['columns'] = array(
	'id'		=> lang('id'),
	'email'		=> lang('email'),
	'created'	=> lang('date'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$r = (array) $row;
	$r['created'] 	=$row->_created;
	//$r['action'] 	= $_row_action($row);

	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);


?>
