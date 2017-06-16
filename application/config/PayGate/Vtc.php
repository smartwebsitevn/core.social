<?php

$config = [

	'name' => 'Vtc',

	'desc' => 'Vtc payment gateway',

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
		'website_id' => [
			'name'  => 'Website ID',
			'rules' => 'required',
		],

		'receiver_acc' => [
			'name'  => 'Receiver account',
			'rules' => 'required',
		],

		'secret_key' => [
			'name'  => 'Secret key',
			'rules' => 'required',
		],
	    
	    'payment_method' => [
	        'name'  => 'Payment method (1 = VND, 2 = USD)',
	        'rules' => 'required',
	    ],
	     
	    'language' => [
	        'name'  => 'Ngôn ngữ (vi | en)',
	        'rules' => 'required',
	    ],
	],

    'withdraw' => [
    
        'acc_id' => [
            'name'  => 'VTC Account id',
            'rules' => 'required',
        ],
    
        'acc_name' => [
            'name'  => 'VTC Account name',
            'rules' => 'required',
        ],
    
    ],
    

];
