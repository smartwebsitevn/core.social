<?php

$config = [

	'name' => 'Coinpayments',

	'desc' => 'Coinpayments payment gateway',

	'version' => '1.0.0',

	'setting' => [

		'public_key' => [
			'name'  => 'Public Key',
			'rules' => 'required',
		],

		'private_key' => [
			'name'  => 'Private Key',
			'rules' => 'required',
		],
	    
	    'currency' => [
			'name'  => 'Currency code',
			'rules' => 'required',
	        'value' => 'ETH'
		],
	  
	],

	'withdraw' => [

		'receiving_merchant_id' => [
			'name'  => 'Receiving merchant ID',
			'rules' => 'required',
		],

	],

];
