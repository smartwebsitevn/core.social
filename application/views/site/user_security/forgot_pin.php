<?php 
	echo macro('mr::form')->form(array(

		'action'		=> $action,
		'title' 		=> lang('title_forgot_pin'),
		'btn_submit' 	=> lang('btn_forgot_pin'),
		
		'rows' => array(
			
			array(
				'param' => 'password_old',
				'type' 	=> 'password',
				'desc' 	=> lang('note_security_password'),
				'req' 	=> true,
			),
		
		),
		
	));
