<!-- Main content wrapper -->
<?php

$_macro = $this->data;


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
	'action' 	=> lang('action'),
);

$_data_name = function ($row) {
	ob_start(); ?>

	<a href="<?php echo $row->_url_view; ?>" target="_blank" style="float:left; margin-right: 5px;">
		<img src="<?php echo $row->image->url_thumb; ?>" height="40px" width="50px" />
	</a>
	<a href="<?php echo $row->_url_view; ?>" target="_blank" >
		<?php echo $row->name; ?>
	</a>
	<br>

	<?php return ob_get_clean();
};
$_rows = array();
foreach ($list as $row)
{
	$v = ($row->status) ? 'on' : 'off';
	$r = (array) $row;
	$r['name'] 	=  $_data_name($row);
	$r['status'] 	= macro()->status_color($v) ;;
	//$r['action'] 	= $_row_action($row);
	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

