<?php

$config = [

	'name' => 'Ngân hàng',

	'desc' => 'Thanh toán qua các ngân hàng',

	'version' => '1.0.0',

	'setting' => [],

	'withdraw' => [

		'bank' => [
			'name'  => 'Tên ngân hàng',
			'type'  => 'select',
			'rules' => 'required',
		],
        /*
		'acc_id' => [
			'name'  => 'Số tài khoản',
			'rules' => 'required',
		],

		'acc_name' => [
			'name'  => 'Chủ tài khoản',
			'rules' => 'required',
		],

		'branch' => [
			'name'  => 'Chi nhánh',
			'rules' => 'trim',
		],
		*/

	],

];
