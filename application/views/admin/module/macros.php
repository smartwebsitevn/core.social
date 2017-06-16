<?php

/**
 * Toolbar
 */
$this->register('toolbar', function($module)
{
	$toolbar = array();
	
	
	$setting = t('module')->{$module}->setting_get_config();
	
	if ( ! empty($setting))
	{
		$toolbar[] = array(
			'title' => lang('setting'),
			'url' => admin_url("md-{$module}/setting"),
		);
	}
	
	
	$tables = t('module')->{$module}->table_get_config();
	
	foreach ($tables as $key => $row)
	{
		$toolbar[] = array(
			'title' => $row['name'],
			'url' => admin_url("md-{$module}/{$key}/list"),
		);
	}
	
	return $toolbar;
});
