<!-- Main content wrapper -->
<?php

$_macro = $this->data;
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

//$_macro['table']['sort'] 	= true;
//$_macro['table']['sort_url_update'] = $sort_url_update;



$_macro['table']['columns'] = array(
	'id' 		=> lang('id'),
	'title'		=> lang('title'),
	'status'		=> lang('status'),
	'created'	=> lang('created'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$v=($row->status) ? 'on' :'off';
	$r = (array) $row;
	$r['status'] 	=  macro()->status_color($v); ;
	$r['created'] 	= $row->_created;
	//$r['action'] 	= $_row_action($row);
	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);


?>
	
	
