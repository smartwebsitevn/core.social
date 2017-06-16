<?php
	$_row_pin_old = function() use ($pin_old)
	{
		if ($pin_old)
		{
			return t('html')->hidden('pin_old', $pin_old);
		}
		
		return $row_pin_old = array(
			'param' => 'pin_old',
			'type' 	=> 'password',
			'req' 	=> true,
		);
	};
	
	echo macro('mr::form')->form(array(

		'action'	=> $action,
		'title' 	=> lang('title_change_pin'),
		
		'rows' => array(
			
			$_row_pin_old(),
			
			array(
				'param' => 'pin',
				'type' 	=> 'password',
				'name' 	=> lang('pin_new'),
				'req' 	=> true,
			),
			
			array(
				'param' => 'pin_confirm',
				'type' 	=> 'password',
				'req' 	=> true,
			),
		
		),
		
	));
