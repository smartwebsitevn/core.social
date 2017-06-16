<?php
	echo macro('mr::form')->form(array(
	
		'action'	=> $action,
		'title'		=> lang('title_verify_verify'),
		'data'		=> (array) $user_verify,
		
		'rows' => array(
			
			array(
				'param' 	=> 'name',
				'name' 		=> lang('full_name'),
				'req' 		=> true,
			),
			
			array(
				'param' 	=> 'phone',
				'req' 		=> true,
			),
			
			array(
				'param' 	=> 'address',
				'req' 		=> true,
			),
			
			array(
				'param' 	=> 'card_no',
				'req' 		=> true,
			),
			
			array(
				'param' 	=> 'card_place',
				'req' 		=> true,
			),
			
			array(
				'param' 	=> 'card_date',
				'type' 		=> 'date',
				'req' 		=> true,
			),
			
			/* array(
				'param' 	=> 'paypal_emails',
				'type' 		=> 'textarea',
				'value' 	=> ( ! empty($user_verify->paypal_emails))
								? implode("\n", $user_verify->paypal_emails)
								: '',
				'desc' 		=> lang('note_paypal_emails'),
			), */
			
		),
	
	));
