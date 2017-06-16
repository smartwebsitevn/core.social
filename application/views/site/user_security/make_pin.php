<?php
	echo macro('mr::form')->form(array(

		'action'	=> $action,
		'title' 	=> lang('title_make_pin'),
		
		'rows' => array(
			
			array(
				'param' => 'password_old',
				'type' 	=> 'password',
				'req' 	=> true,
			),
			
			array(
				'param' => 'pin',
				'type' 	=> 'password',
				'req' 	=> true,
			),
			
			array(
				'param' => 'pin_confirm',
				'type' 	=> 'password',
				'req' 	=> true,
			),
		
		),
		
	));
