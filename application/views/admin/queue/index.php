<?php
	$list_status = ['pending', 'handling'];
	
	$_row_action = function($row)
	{
		ob_start();?>
		
		<?php if ($row->_can_view): ?>
			<a href="<?php echo $row->_url_view; ?>" class="lightbox"
			><?php echo lang('detail'); ?></a>
		<?php endif; ?>
		
		<?php return ob_get_clean();
	};
	
	
	$_macro = $this->data;
	$_macro['toolbar'] = array();
	$_macro['table'] = array_only($this->data, array('total', 'orders', 'actions', 'pages_config'));

	$_macro['table']['filter']['data'] = $filter;
	$_macro['table']['filter']['rows'] = array(
	
		array(
			'param' => 'key',
			'attr' 	=> array('style' => 'width:150px;'),
		),
	
		array(
			'param' 	=> 'status',
			'type' 		=> 'select',
			'values' 	=> macro('mr::form')->make_options(array_combine($list_status, $list_status)),
		),
	
		array(
			'param' 	=> 'created',
			'type' 		=> 'date',
			'name' 		=> lang('from_date'),
		),
	
		array(
			'param' 	=> 'created_to',
			'type' 		=> 'date',
			'name' 		=> lang('to_date'),
		),
	
	);

	$_macro['table']['columns'] = array(
		'key'		=> lang('key'),
		'status'	=> lang('status'),
		'created'	=> lang('created'),
		'handled'	=> lang('handled'),
		'action'	=> lang('action'),
	);
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['created'] 	= $row->_created_full;
		$r['handled'] 	= $row->_handled_full;
		$r['action'] 	= $_row_action($row, ['view']);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);