<?php

/**
 * Toolbar
 */
$this->register('toolbar', function()
{
	return array(
		array(
			'url' 	=> admin_url('lang'),
			'title' => lang('list'),
		),
	);
});
