<?php

/**
 * Toolbar
 */
$this->register('toolbar', function()
{
	return array(
		array(
			'url' 	=> admin_url('currency'),
			'title' => lang('list'),
		),
	);
});
