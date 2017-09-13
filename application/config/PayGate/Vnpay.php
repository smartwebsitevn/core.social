<?php

$config = [

	'name' => 'Vnpay',

	'desc' => 'Vnpay payment gateway',

	'version' => '1.0.0',

	'setting' => [

	    'url'     => [
			'name'  => 'Url',
			'rules' => 'required',
	        'value' => 'http://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
	    	'desc'  => 'Link test: http://sandbox.vnpayment.vn/paymentv2/vpcpay.html'
		],
	    
			
		'merchant' =>  [
	        'name'  => 'Merchant',
	        'rules' => 'required',
			'value' => 'VNPAY'
	    ],
			
	    'terminal'     => [
	        'name'  => 'Terminal',
	        'rules' => 'required',
	    	'value' => 'TENMIEN1'
	    ],
		
		'key' => [
			'name'  => 'Secret key',
			'rules' => 'required',
			'value' => 'VEANLTVAXYICCVKTRMDDLGBNGZFZHXXL'
		],
			
		'ordertype' => [
				'name'  => 'Order type',
				'rules' => 'required',
				'value' => 'topup',
				'desc'  => 'topup = Nạp tiền điện thoại | billpayment = Thanh toán hóa đơn | fashion = Thời trang '
		],
			
			
			
	    'language' => [
	        'name'  => 'Ngôn ngữ (vn | en)',
	        'rules' => 'required',
	    	'value' => 'vn',
	    ],
	],


];
