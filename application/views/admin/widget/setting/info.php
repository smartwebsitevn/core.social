<?php
	$info = (isset($info)) ? $info : new stdClass();
	$info = set_default_value($info, array('id', 'name', 'module', 'widget', 'region', 'status', 'sort_order', 'setting'));
	$info = set_default_value($info, array('url_show', 'url_hide'), array());

	$_data_region = function ($regions, $info)
	{
		ob_start(); ?>
		<select name="region" class="form-control" _autocheck="true">
			<option value=""></option>
			<?php foreach ($regions as $k => $v): ?>
				<option value="<?php echo $k; ?>" <?php echo form_set_select($k, $info->region); ?>>
					<?php echo $v['name']; ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php return ob_get_clean();
	};

	$_data_layout = function ($layouts, $info)
	{
		ob_start(); ?>
		<select name="layout" class="form-control" _autocheck="true">
			<option value=""></option>
			<?php foreach ($layouts as $k => $v): ?>
				<option value="<?php echo $k; ?>" <?php echo form_set_select($k, $info->layout); ?>>
					<?php echo $v['name']; ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php return ob_get_clean();
	};
	?>

	<!-- Info -->
	<div class="form-group  ">
		<b class="col-sm-3 fontB blue f14 ">
			<?php echo lang('tab_info'); ?>:
		</b><br>
	</div>
	<?php

	echo macro('mr::form')->row(array(
		'param' => 'name',
		'value' => $info->name,
	));

	echo macro('mr::form')->row(array(
		'param' => 'status', 'type' => 'bool_status',
		'value' => $info?$info->status:1,
	));

	echo macro('mr::form')->row(array(
		'param' => 'region',
		'type'  => 'ob',
		'value' => $_data_region($regions, $info),
	));

	/*echo macro('mr::form')->row( array(
		'param' 	=> 'layout','type' 		=> 'ob',
		'value'=>$_data_layout($layouts,$info),
	));*/

	echo macro('mr::form')->row(array(
		'param' => 'sort_order',
		'value' => $info->sort_order,
	));

	echo macro('mr::form')->row([
		'param'  => 'status_auth',
		'type'   => 'select',
		'value'  => data_get($info, 'status_auth'),
		'values' => lang_map(['user', 'guest'], 'status_auth_'),
		'desc'   => lang('notice_option_apply'),
	]);

	//echo '<div id="advanced_setting" style="display:none;">  ';
	echo macro('mr::form')->row(array(
		'param' => 'url_show',
		'type'  => 'textarea',
		'value' => implode("\n", $info->url_show),
		'desc'  => lang('note_line_value'),
	));
	echo macro('mr::form')->row(array(
		'param' => 'url_hide',
		'type'  => 'textarea',
		'value' => implode("\n", $info->url_hide),
		'desc'  => lang('note_line_value'),
	));
	//echo '</div>';


	?>
	<!--	<div class="">
			<a href="#url" onclick="jQuery('#advanced_setting').toggle('blind', 200); return false;">
				<?php /*echo lang('button_advanced_setting'); */ ?>
			</a>
			<div class="clear"></div>
		</div>-->