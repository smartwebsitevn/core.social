<!-- Main content wrapper -->
<?php

$_macro = $this->data;
/*$_macro['toolbar'] =array(
	array(
		'url' 	=> admin_url('menu/add'),
		'title' => lang('add'),
		'icon' => 'plus',
		'attr' => array('class'=>'lightbox'),
	),
	array(
		'url' 	=> admin_url('menu'),
		'title' => lang('list'),
		'icon' => 'list',
	));*/
$_macro['toolbar_sub'] = array(
	array('url' => admin_url('menu_item'), 'title' => lang('mod_menu_item') /* 'icon' => 'plus',*/),
	array('url' => admin_url('menu'), 'title' => lang('mod_menu'),'attr'=>array('class'=>'active') /*'icon' => 'list'*/)
);

$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $url_update_order;

$_macro['table']['columns'] = array(
	'name'		=> lang('title'),
	'key'		=> lang('key'),
	'sort_order'		=> lang('sort_order'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$r = (array) $row;
	//$r['action'] 	= $_row_action($row);
	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);


?>



