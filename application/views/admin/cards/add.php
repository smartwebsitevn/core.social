<?php
	$_macro = $this->data;
	$_macro['form']['data'] = isset($info) ? (array) $info : array();
	
	$_macro['form']['rows'][] = array(
		'param' => 'quantity',
		'value' => '10',
		'req' 	=> true,
	);
	
	$_macro['form']['rows'][] = array(
		'param' => 'amount',
		'name' 	=> lang('card_amount'),
		'req' 	=> true,
		'attr' 	=> ['class' => 'format_number'],
	);
	
	$_macro['form']['rows'][] = array(
		'param' => 'expire',
		'type' 	=> 'date',
		'value' => get_date(add_time(now(), ['y' => 5])),
		'req' 	=> true,
	);
	
	echo macro()->page($_macro);