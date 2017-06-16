<?php

$_row_feature = function ($row) {
    ob_start();
    ?>
    <a class="toggle_action iIcon iStar <?php if ($row->_can_feature_del) echo 'on'; ?>"
       _url_on="<?php echo $row->_url_feature; ?>" _url_off="<?php echo $row->_url_feature_del; ?>"
       _title_on="<?php echo lang('feature_set'); ?>" _title_off="<?php echo lang('feature_del'); ?>"
        ></a>
    <?php return ob_get_clean();
};
	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	
	$_macro['table']['sort'] 	= true;
	$_macro['table']['sort_url_update'] = $sort_url_update;

	$_macro['table']['filters'] = array(
		array(
			'name' => lang('name'), 'param' => 'name',
			'value' => $filter['name'],
		),

		array(
			'name' => lang('status'), 'type' => 'select', 'param' => 'status',
			'value' => $filter['status'],
			'values' => array('on' => lang('active'), 'off' => lang('unactive')),
		),

		array(
			'name' => lang('feature'), 'type' => 'select', 'param' => 'feature',
			'value' => $filter['feature'],
			'values' => array('on' => lang('yes'), 'off' => lang('no')),
		),


	);

$_macro['table']['columns'] = array(
		'name' 		=> lang('name'),
		'feature' => lang('feature'),
		'status' => lang('status'),
		'action' 	=> lang('action'),
	);
	
	$_rows = array();
	foreach ($list as $row)
	{
		$status = ($row->status) ? 'on' : 'off';
		$feature = ($row->feature) ? 'on' : 'off';
		$r = (array) $row;
		//$r['feature'] = macro()->status_color($feature);
		$r['feature'] = $_row_feature($row);
		$r['status'] = macro()->status_color($status);
		//$r['action'] 	= $_row_action($row);
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);