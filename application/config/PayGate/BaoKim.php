<?php

$config = [

	'name' => 'BaoKim',

	'desc' => 'BaoKim payment gateway',

	'version' => '1.0.0',

	'setting' => [

	    'url'     => [
			'name'  => 'Url',
			'rules' => 'required',
		],
	    'ip'     => [
	        'name'  => 'IP (mỗi IP cách nhau bằng dấu ",")',
	        'rules' => 'required',
	    ],
		'business' => [
			'name'  => 'Business',
			'rules' => 'required',
		],

		'merchant_id' => [
			'name'  => 'Merchant id',
			'rules' => 'required',
		],

		'secure_pass' => [
			'name'  => 'Secure pass',
			'rules' => 'required',
		],

	],

	'withdraw' => [

		'acc_id' => [
			'name'  => 'Baokim Account id',
			'rules' => 'required',
		],

		'acc_name' => [
			'name'  => 'Baokim Account name',
			'rules' => 'required',
		],

	],

];
