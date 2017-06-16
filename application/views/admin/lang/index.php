<!-- Main content wrapper -->
<?php

	$_macro = $this->data;
	//$_macro['toolbar'] = array();
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	
	//$_macro['table']['sort'] 	= true;
	//$_macro['table']['sort_url_update'] = $sort_url_update;


	$_macro['table']['columns'] = array(
		'name' 		=> lang('name'),
		'directory'		=> lang('directory'),
		'status'	=> lang('status'),
		'action' 	=> lang('action'),
	);
	$path = public_url().'/img/world/';
	$_rows = array();
	foreach ($list as $row)
	{
		$v = ($row->status) ? 'on' : 'off';
		$r = (array) $row;
		/*if ($row->_is_default){
			$r['name'] .='<img class="tipS right" title="'.lang('lang_default').'" src="'.public_url('admin').'/images/icons/color/set_default.png" />';
		}*/
		$r['name'] 	=t('html')->img($path.strtolower($row->code).'.gif'). ' '.$row->name;
		$r['status'] 	= macro()->status_color($v,lang($v)) ;
		//$r['action'] 	= $_row_action($row);
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);
	
	
	?>
<div class="alert alert-danger"><?php echo lang('note_lang_delete') ?></div>
	
	
