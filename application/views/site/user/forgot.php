<?php

echo macro('mr::form')->form([

	'action'     => $action,
	'title'      => lang('title_forgot'),
	'btn_submit' => lang('button_send_password'),

	'rows' => [
			
		[
			'param' => 'email_valid',
			'name' 	=> lang('email'),
			'value' => array_get($input, 'email'),
			'req' 	=> true,
		],
		
		macro('mr::form')->captcha($captcha),

	],

]);
