<?php
	$_macro = $this->data;
	$_macro['form']['data'] = isset($info) ? (array) $info : array();

	$_macro['form']['rows'][] = array(
		'param' => 'code',
		'name' 	=> lang('card_code'),
		'attr' 	=> array('disabled' => 'disabled'),
	);

	$_macro['form']['rows'][] = array(
		'param' => 'serial',
		'name' 	=> lang('card_serial'),
		'attr' 	=> array('disabled' => 'disabled'),
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
		'req' 	=> true,
	);
	
	/*$_macro['form']['rows'][] = array(
		'param' 	=> 'status',
		'type' 		=> 'bool',
		'values' 	=> array(lang('action_used'), lang('action_unused')),
	);*/
	
	echo macro()->page($_macro);