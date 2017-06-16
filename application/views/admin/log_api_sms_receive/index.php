<?php
	$_macro = $this->data;
	$_macro['toolbar'] = array();
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	

	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'value',
		'name' 		=> lang('key'),
		'value' 	=> $filter['value'],
		'attr' 		=> array(
			'style' => 'width: 250px;',
			'placeholder' => 'Nhập Số điện thoại, Nội dung hoặc Phản hồi',
		),
	);
	
	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'created',
		'type' 		=> 'date',
		'name' 		=> lang('from_date'),
		'value' 	=> $filter['created'],
	);
	
	$_macro['table']['filter']['rows'][] = array(
		'param' 	=> 'created_to',
		'type' 		=> 'date',
		'name' 		=> lang('to_date'),
		'value' 	=> $filter['created_to'],
	);
	
	
	$_macro['table']['columns'] = array(
		'phone' 	=> lang('sms_phone'),
		'message' 	=> lang('sms_message'),
		'port' 		=> lang('sms_port'),
		'response' 	=> lang('sms_response'),
		'created' 	=> lang('time'),
	);
	
	
	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['created'] = format_date($row['created'], 'full');
		
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);