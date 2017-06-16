<?php

$config = array(
	
	'menu' => array(
		'account'		=> array( 'user', 'user_verify','user_security'),
		'tran'			=> array('tran',
								'deposit',
								'withdraw', 
 								'deposit_bank',
								'tran_banking', 
								),
      	),
	
	'menu_url' => array(
		
		'tran' => array(
			'deposit' 	=> admin_url('tran/deposit'),
			'withdraw' 	=> admin_url('tran/withdraw'),
		),
		
	),
	'menu_icon_group' => array(
	),
	'menu_lang' => array(),
	
);