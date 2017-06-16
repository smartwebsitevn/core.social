<!-- Main content wrapper -->
<?php

$_data_name =function($modules,$row)
{
	//pr($modules);
	ob_start();?>
	<?php echo $row->name; ?><br/>
	<small class="f12">[Module <?php echo $modules[$row->module]->name.': '.$modules[$row->module]->widget[$row->widget]['name']; ?>]</small>

	<?php return ob_get_clean();
};
$_data_module =function($modules,$filter )
{
	ob_start();?>
	<select name="module_widget" class="form-control" onchange="this.form.submit()">
		<option value="">-=<?php echo lang('module'); ?>=-</option>
		<?php foreach ($modules as $m): ?>
			<optgroup label="<?php echo $m->name; ?>">
				<?php foreach ($m->widget as $w => $w_o): ?>
					<?php $v = $m->key.':'.$w; ?>
					<option value="<?php echo $v; ?>" <?php echo form_set_select($v, $filter['module_widget']); ?>>
						<?php echo $w_o['name']; ?>
					</option>
				<?php endforeach; ?>

			</optgroup>
		<?php endforeach; ?>
	</select>
	<?php return ob_get_clean();
};
$_data_region =function($regions,$filter )
{
	ob_start();?>
	<select name="region" class="form-control" onchange="this.form.submit()">
		<option value="">-=<?php echo lang('region'); ?>=-</option>
		<?php foreach ($regions as $k => $v): ?>
			<option value="<?php echo $k; ?>" <?php echo form_set_select($k, $filter['region']); ?>>
				<?php echo $v['name']; ?>
			</option>
		<?php endforeach; ?>
	</select>
	<?php return ob_get_clean();
};
$_data_layout =function($layouts,$filter )
{
	ob_start();?>
	<select name="layout" class="form-control" onchange="this.form.submit()">
		<option value="">-=<?php echo lang('layout'); ?>=-</option>
		<?php foreach ($layouts as $k => $v): ?>
			<option value="<?php echo $k; ?>" <?php echo form_set_select($k, $filter['layout']); ?>>
				<?php echo $v['name']; ?>
			</option>
		<?php endforeach; ?>
	</select>
	<?php return ob_get_clean();
};


$_macro = $this->data;
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $url_update_order;

$_macro['table']['filters'] = array(
	array(  'param' => 'name',
		   'value' 	=>$filter['name'],
	),
	array(
		 'type'=> 'ob',
		'value' => $_data_module($modules,$filter),
	),
	/*array(
		'type'=> 'ob',
		'value' => $_data_layout($layouts,$filter),
	),*/
	array(
		 'type'=> 'ob',
		'value' => $_data_region($regions,$filter),
	),

	array(
		'name' 	=>  lang('status'), 'type'=> 'select',	'param' => 'status',
		'value' => $filter['status'],
		'values' =>array('on'=>lang('active'),'off'=>lang('unactive')),
	),
);

$_macro['table']['columns'] = array(
	'name' 		=> lang('name'),
	'region'	=> lang('region'),
	'status'	=> lang('status'),
	'action' 	=> lang('action'),
);

$_rows = array();
foreach ($list as $row)
{
	$v = ($row->status) ? 'on' : 'off';

	$r = (array) $row;

	$r['name'] 	= $_data_name($modules,$row);
	$r['region'] 	= $row->_region;
	//$r['region'] 	= $row->_region.'<br>[Layout: '.$layouts[$row->layout]['name'] .']';
	//$r['status'] 	= macro()->status_color($v,lang($v)) ;
	$r["status"] = '<a class="toggle_action iStar iIcon '.($row->_can_off ? 'on' : '').'"
								_url_on="'.$row->_url_on.'" _url_off="'.$row->_url_off.'"
								_title_on="'.lang('on').'" _title_off="'.lang('off').'"
							></a>';
	//$r['action'] 	= $_row_action($row);

	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

