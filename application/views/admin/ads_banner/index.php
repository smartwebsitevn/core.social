<!-- Main content wrapper -->
<?php

$_macro = $this->data;

$_macro['toolbar_sub'] = array(
	array('url' => admin_url('ads_banner'), 'title' => lang('mod_ads_banner') ,'attr'=>array('class'=>'active') /* 'icon' => 'plus',*/),
	array('url' => admin_url('ads_location'), 'title' => lang('mod_ads_location'),/*'icon' => 'list'*/)
);

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $sort_url_update;


$_macro['table']['filters'] = array(
	array( 'param' => 'ads_location_id', 'name'=>lang('ads_location'), 'type'=>'select',
		'value' => $filter['ads_location_id'], 'values_row' => array($locations, 'id', 'name'),
	),
	array(
		'name' 	=>  lang('status'), 'type'=> 'select',	'param' => 'status',
		'value' => $filter['status'],
		'values' =>array('on'=>lang('active'),'off'=>lang('unactive')),
	)
);


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
	'sort_order' 		=> lang('sort_order'),
	'banner'		=> lang('banner'),
	'name'		=> lang('name'),
	'location'		=> lang('location'),
	'created'	=> lang('created'),
	'date_expire'	=> lang('date_expire'),
	//'date_left'	=> lang('date_left'),
	'status'	=> lang('status'),
	'count_click'	=> 'Click',// lang('click'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$v = ($row->status) ? 'on' : 'off';
	$r = (array) $row;

	$r['banner'] 	='<a href="'.prep_url($row->url).'" target="_blank">
							<img src="'.$row->image.'" height="50" style="max-width:100%;">
						</a>';
	$r['name'] 	=$row->name;
	$r['location'] 	=$row->_location_name;
	$r['created'] 	=$row->_created;
	$r['date_expire'] 	= $row->end ? $row->_end : '';
	//$r['date_left'] 	=$row->_days_left;
	$r['status'] 	= macro()->status_color($v) ;
	$r['count_click'] 	= number_format($row->count_click) ;
	//$r['action'] 	= $_row_action($row);

	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);


?>
