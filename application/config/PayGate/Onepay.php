<?php

$config = [

	'name' => 'Onepay',

	'desc' => 'Onepay payment gateway',

	'version' => '2.0.0',

	'setting' => [

		'merchant_id' => [
			'name'  => 'Merchant ID',
			'rules' => 'required',
		],

		'access_code' => [
			'name'  => 'Access Code',
			'rules' => 'required',
		],

		'secure_secret' => [
			'name'  => 'Secure Secret',
			'rules' => 'required',
		],
	    
	    'ptype' => [
	        'name'  => 'LOCAL/INTER (Nhập một trong hai từ khóa này)',
	        'rules' => 'required',
	    ],
	    
	    'sandbox' => [
	        'name'  => 'SANDBOX (Nhập số 0 hoặc 1)',
	        'rules' => 'required',
	    ],
	    
	    'shopname' => [
	        'name'  => 'Merchant Name (Nhập tùy ý)',
	        'rules' => 'required',
	    ],

	],



];
