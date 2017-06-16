
<?php
	$_macro = $this->data;
	$_macro['toolbar'] = array();
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	//$_macro['table']['sort'] 	= true;
	//$_macro['table']['sort_url_update'] = $sort_url_update;

	$_macro['table']['columns'] = array(
		'name'         => lang('name'),
		'code'         => lang('code'),
		'value'        => lang('rate'),
		//'decimal'	=> lang('decimal'),
		'purse_prefix' => lang('purse_prefix'),
		'status'       => lang('status'),
		//'show'	=> lang('show'),
		'action'       => lang('action'),
	);

	$_rows = array();
	foreach ($list as $row)
	{
		$v = ($row->status) ? 'on' : 'off';
		$r = (array) $row;
		if ($row->_is_default)
		{
			$r['name'] .= '<img class="tipS right" title="' . lang('currency_default') . '" src="' . public_url('admin') . '/images/icons/color/set_default.png" />';
		}
		$r['value'] = (float) $r['value'];
		$r['status'] = macro()->status_color($v, lang($v));
		//$r['show'] 	= ($row->show) ? lang('on') : lang('off');
		$r['action'] = macro('mr::table')->actions_data($row, ['edit']);
		$_rows[] = $r;
	}

	$_macro['table']['rows'] = $_rows;
	$_macro['table']['actions_row'] = ['edit'];
	echo macro()->page($_macro);
	
	
	?>
	
	
