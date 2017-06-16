<?php
	$_row_image = function($row)
	{
		ob_start(); ?>
		
			<a href="<?php echo $row->image->url; ?>" target="_blank"
			><img src="<?php echo $row->image->url; ?>" style="height:50px; max-width:200px;">
			</a>
		
		<?php return ob_get_clean();
	};
	



	$_macro = $this->data;
	$_macro['toolbar_sub'] = array(
		array('url' => admin_url('slider_item'), 'title' => lang('mod_slider_item'),'attr'=>array('class'=>'active')),

		array('url' => admin_url('slider'), 'title' => lang('mod_slider') , /* 'icon' => 'plus',*/),
			);

	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	
	$_macro['table']['sort'] 	= true;
	$_macro['table']['sort_url_update'] = $sort_url_update;
	
	$_macro['table']['columns'] = array(
		'image' 	=> lang('image'),
		'name' 	=> lang('name'),
		'slider'	=> lang('slider'),
		'status'	=> lang('status'),
		'sort_order'	=> lang('sort_order'),
		'action' 	=> lang('action'),
	);
	
	$_rows = array();
	foreach ($list as $row)
	{
		$v = ($row->status) ? 'on' : 'off';
		$r = (array) $row;
		$r['image'] 	= $_row_image($row);
		$r['slider'] 	= $row->slider->name;
		$r['status'] 	= macro()->status_color($v) ;
		//$r['action'] 	= $_row_action($row);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);