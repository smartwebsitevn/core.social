<!-- Main content wrapper -->
<?php

$_macro = $this->data;

$_macro['toolbar_sub'] = array(
	array('url' => admin_url('ads_banner'), 'title' => lang('mod_ads_banner') ,/* 'icon' => 'plus',*/),
	array('url' => admin_url('ads_location'), 'title' => lang('mod_ads_location'),'attr'=>array('class'=>'active') /*'icon' => 'list'*/)
);

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

//$_macro['table']['sort'] 	= true;
//$_macro['table']['sort_url_update'] = $sort_url_update;

/*$_macro['table']['filters'] = array(
	array(  'name' 	=> lang('title'),'param' => 'name',
		'value' 	=>$filter['title'],
	),
	array(
		'name' 	=>  lang('status'), 'type'=> 'select',	'param' => 'status',
		'value' => $filter['status'],
		'values' =>array('on'=>lang('active'),'off'=>lang('unactive')),
	)

);*/

$_macro['table']['columns'] = array(
	'id' 		=> lang('id'),
	'name'		=> lang('name'),
	'code'		=> lang('code'),
	'banner_size'	=> lang('banner_size'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$r = (array) $row;
	$r['banner_size'] 	=$row->banner_width.'x'.$row->banner_height;
	//$r['action'] 	= $_row_action($row);

	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);


?>

