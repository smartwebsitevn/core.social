<?php
	echo macro('mr::form')->form(array(

		'action'	=> $action,
		'title' 	=> lang('title_user_security'),
		
		'rows' => array(
			
			array(
				'param' 	=> 'method',
				'type' 		=> 'select',
				'name' 		=> lang('security_method'),
				'desc' 		=> lang('note_security_method'),
				'value' 	=> $user->security_method,
				'values' 	=> array_combine($methods, array_map('lang', $methods)),
				'req' 		=> true,
			),
			
			mod('user_security')->form(),
			
		),
		
	));