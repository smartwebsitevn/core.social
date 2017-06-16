<?php
$_data_country =function($param,$code, $countries)
{
	$path = public_url().'/img/world/';
	ob_start();?>
	<select name="<?php echo $param; ?>[]" multiple="multiple" class="form-control select_multi"
		<option value="">-=<?php echo lang('layout'); ?>=-</option>
		<?php foreach ($countries as $group): ?>
					<optgroup label="<?php echo $group->name; ?>">

					<?php foreach ($group->countries as $v): ?>
					<option data-image="<?php echo $path.strtolower($v->id).'.gif'?>" value="<?php echo $v->id; ?>" <?php echo form_set_select($v->id, $code); ?>>
			    	<?php echo $v->name; ?>
			        </option>
					<?php endforeach; ?>

					</optgroup>
				<?php endforeach; ?>


	</select>
	<?php return ob_get_clean();
};
echo macro('mr::form')->row(array(
    'param' => 'date_format', 'type' => 'select',
    'value' => $setting['date_format'], 'values_single' => $date_formats, 'req' => 1,
));

echo macro('mr::form')->row(array(
    'param' => "timezone", 'name' => lang('timezone'), 'type' => 'select',
    'value' => $setting['timezone'], 'values' => $timezones, 'req' => 1,
));

echo macro('mr::form')->row(array(
    'param' => "admin_language", 'name' => lang('admin_language'), 'type' => 'select',
    'value' => $setting['admin_language'], 'values_row' => array($languages, 'id', 'name'), 'req' => 1,
));
echo macro('mr::form')->row(array(
    'param' => "site_language", 'name' => lang('site_language'), 'type' => 'select',
    'value' => $setting['site_language'], 'values_row' => array($languages, 'id', 'name'), 'req' => 1,
));
/*echo macro('mr::form')->row( array(
	'param' 	=> 'banned_countries','type' 		=> 'select_multi',
	'value' 	=>$setting['banned_countries'],'values_row'=>array($countries,'code','name')
));*/


echo macro('mr::form')->row(array(
    'param' => 'banned_countries', 'type' => 'ob',
    'value' => $_data_country('banned_countries', $setting['banned_countries'], $countries),

));

echo macro('mr::form')->row(array(
    'param' => 'banned_ips', 'type' => 'textarea',
    'value' => $setting['banned_ips'],
    'desc' => lang('banned_ips_note'),
));
echo macro('mr::form')->row(array(
    'param' => 'invoice_pre_key', 'name' => lang('invoice_pre_key'),
    'value' => $setting['invoice_pre_key'],
));
echo macro('mr::form')->row(array(
    'param' => 'invoice_pre_number', 'name' => lang('invoice_pre_number'),
    'value' => $setting['invoice_pre_number'],
));


echo macro('mr::form')->row(array(
    'param' => 'length_unit', 'type' => 'select',
    'value' => $setting['length_unit'], 'values' => $unit_lengths,
));
echo macro('mr::form')->row(array(
    'param' => 'weight_unit', 'type' => 'select',
    'value' => $setting['weight_unit'], 'values' => $unit_weights,
));
echo macro('mr::form')->row(array(
    'param' => 'file_unit', 'type' => 'select',
    'value' => $setting['file_unit'], 'values' => $unit_files,
));


?>
