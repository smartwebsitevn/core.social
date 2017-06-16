<!-- Main content wrapper -->
<?php
$_data_type =function() use($types,$filter)
{

	ob_start();?>
	<select name="type" class="form-control " onchange="this.form.submit()">
		<?php foreach ($types as $type):?>
					<option value="<?php echo $type; ?>" <?php echo form_set_select($type,$filter['type']); ?>>
			    	<?php echo lang($type); ?>
			        </option>
				<?php endforeach; ?>


	</select>
	<?php return ob_get_clean();
};

$_macro = $this->data;

$_macro['toolbar'] = array(
	array('url' => admin_url('cat/add/'.$type), 'title' => lang('add'), 'icon' => 'plus','attr'=>array('class'=>'btn btn-danger')),
	array('url' => admin_url('cat'), 'title' => lang('list'), 'icon' => 'list','attr'=>array('class'=>'btn btn-primary')),
);
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

$_macro['table']['sort'] 	= true;
$_macro['table']['sort_url_update'] = $sort_url_update;

$_macro['table']['filters'] = array(
	array( 'param' => 'type','type'=>'ob',
		'value' => $_data_type()
	),

);
/*$_macro['table']['filters'] = array(
	array( 'param' => 'type','type'=>'select',
		'value' => $filter['type'], 'values_single' => $types,'values_opts'=>array('value_required'=>1)
	),

);*/
$_macro['table']['columns'] = array(
	'id' 		=> lang('id'),
	'name'		=> lang('name'),
	'status'	=> lang('status'),
	'action' 	=> lang('action'),
);
$_rows = array();
foreach ($list as $row)
{
	$v = ($row->status) ? 'on' : 'off';
	$r = (array) $row;
	//$r['name'] 	=$row->_name.$row->_content->name;
	$r['name'] 	=$row->name;
	$r['status'] 	= macro()->status_color($v) ;;
	//$r['action'] 	= $_row_action($row);

	$_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

