<?php

$config = [

	'name' => 'Paypal',

	'desc' => 'Paypal payment gateway',

	'version' => '1.0.0',

	'setting' => [

		'user' => [
			'name'  => 'User',
			'rules' => 'required',
		],
		'pwd' => [
			'name'  => 'Password',
			'rules' => 'required',
		],
		'sign' => [
			'name'  => 'Sign',
			'rules' => 'required',
		],


	],

	'withdraw' => [

		'acc_id' => [
			'name'  => 'Paypal Account id',
			'rules' => 'required',
		],

		'acc_name' => [
			'name'  => 'Paypal Account name',
			'rules' => 'required',
		],

	],

];
