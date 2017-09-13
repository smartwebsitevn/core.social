<?php

$config = [

	'name' => 'NganLuong',

	'desc' => 'NganLuong payment gateway',

	'version' => '3.1.0',

	'setting' => [

		'business'  => [
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
			'name'  => 'NganLuong Account id',
			'rules' => 'required',
		],

		'acc_name' => [
			'name'  => 'NganLuong Account name',
			'rules' => 'required',
		],

	],

];
