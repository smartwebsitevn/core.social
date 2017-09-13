<?php

$config = array(
	//'username' 		=> true,
	//'activation'	=> false,
	//'login_ip' 		=> true,

	'register_allow' => true,
	'register_require_activation' => false,
	'register_banned_countries' => '',

	// cho phep dang nhap h? thong
	'login_allow' => true,
	'login_auth_allow' => true,

	// So lan dang nhap sai de block IP (0: Khong kiem tra)
	'login_fail_count_max' => 10,
	// Thoi gian block IP khi dang nhap sai qua so lan quy dinh (0: Block vinh vien)
	'login_fail_block_timeout' =>  60*60,
	'login_check_ip' => false,

	'balance_block' => false,
	'balance_timeout_from_register' => 0,

	//'types' => ['guest', 'user', 'sms_otp', 'sms_odp'],
);
