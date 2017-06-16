<!-- Main content wrapper -->
<?php

	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

	//$_macro['table']['sort'] 	= true;
	//$_macro['table']['sort_url_update'] = $sort_url_update;


	$_macro['table']['columns'] = array(
		'sort_order' 		=> lang('sort_order'),
		'name'		=> lang('name'),
		'action' 	=> lang('action'),
	);

	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		// $r['created'] 	= $row->_created;
		//$r['action'] 	= $_row_action($row);

		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;

	echo macro()->page($_macro);


	?>

