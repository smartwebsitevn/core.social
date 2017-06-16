<?php 
	echo macro('mr::form')->form(array(

		'action'	=> $action,
		'title' 	=> lang('title_change_pass'),
		
		'rows' => array(
			
			array(
				'param' => 'password_old',
				'type' 	=> 'password',
				'req' 	=> true,
			),
			
			array(
				'param' => 'password',
				'type' 	=> 'password',
				'name' 	=> lang('password_new'),
				'req' 	=> true,
			),
			
			array(
				'param' => 'password_confirm',
				'type' 	=> 'password',
				'req' 	=> true,
			),
		
		),
		
	));