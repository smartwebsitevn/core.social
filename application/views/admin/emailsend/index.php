<?php
	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	

	$_macro['table']['columns'] = array(
		'title'	=> lang('title'),
			'status'	=> lang('status'),
			'created'	=> lang('created'),
			'updated'	=> lang('updated'),
			'statistic'	=> lang('statistic'),
		'action' 	=> lang('action'),
	);
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['status'] = lang('status_'.$row->status);
		$r['created'] = $row->_created_time;
		$r['updated'] = $row->_updated_time;
		$r['statistic'] = '<span class="text-success">'.$row->success.'</span> / <span class="text-danger">'.$row->error.'</span> / <span class="text-primary">'.$row->total.'</span>';
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);