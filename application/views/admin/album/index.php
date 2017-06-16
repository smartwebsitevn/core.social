<!-- Main content wrapper -->
<?php

$_macro = $this->data;
$_macro['toolbar'] = array(
	array('url' => admin_url('album/add/'.$cat), 'title' => lang('add'), 'icon' => 'plus','attr'=>array('class'=>'btn btn-danger')),
	array('url' => admin_url('album'), 'title' => lang('list'), 'icon' => 'list','attr'=>array('class'=>'btn btn-primary')),
);
$_macro['toolbar_sub'] = array(
	array('url' => admin_url('album'), 'title' => lang('mod_album'),'attr'=>array('class'=>'active') /* 'icon' => 'plus',*/),
	array('url' => admin_url('album_cat'), 'title' => lang('mod_album_cat'), /*'icon' => 'list'*/)
);

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $sort_url_update;

$_macro['table']['filters'] = array(
	array( 'param' => 'cat_id','type'=>'select',
		'value' => $filter['cat_id'], 'values_row' => array($cats, 'id', 'name'),'values_opts'=>array('value_required'=>1)
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

