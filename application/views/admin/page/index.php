<!-- Main content wrapper -->
<?php

	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	
	$_macro['table']['sort'] 	= true;
	$_macro['table']['sort_url_update'] = $sort_url_update;

	$_macro['table']['filters'] = array(
					                    array(  'name' 	=> lang('title'),'param' => 'name',
					                            'value' 	=>$filter['title'],
					        				),
					                       array(
					                    	'name' 	=>  lang('status'), 'type'=> 'select',	'param' => 'status',
					                        'value' => $filter['status'],
					    					'values' =>array('on'=>lang('active'),'off'=>lang('unactive')),
					    				),

					              );
					              
	$_macro['table']['columns'] = array(
		'id' 		=> lang('id'),
		'title'		=> lang('title'),
		'status'	=> lang('status'),
		'created'	=> lang('created'),
		/*	'lang'		=> lang('lang'),
			'admin' => lang('admin'),*/
		'action' 	=> lang('action'),
	);

	$_rows = array();
	foreach ($list as $row)
	{
		$v = ($row->status) ? 'on' : 'off';
		$r = (array) $row;
		$r['title'] 	= t('html')->a($row->_url_view,$row->title,array('target'=>'_blank'));
		$r['status'] 	= macro()->status_color($v) ;
		$r['created'] 	= $row->_created;
		$r['lang'] 	= macro('mr::table')->langs_url($row);
		$r['admin'] = (isset($row->admin->name) ? $row->admin->name : '').'<p style="font-size: 11px">('.$row->_updated_time.')</p>';
		//$r['action'] 	= $_row_action($row);
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);
	
	
	?>
	
	
