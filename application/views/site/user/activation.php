<?php

echo macro('mr::form')->form([

	'action'     => $action,
	'title'      => lang('title_activation'),
	'btn_submit' => lang('button_activation'),

	'rows' => [
			
		[
			'param' => 'email_activation',
			'name' 	=> lang('email'),
			'value' => array_get($input, 'email'),
			'req' 	=> true,
		],
		
		macro('mr::form')->captcha($captcha),

	],

]);